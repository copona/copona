<?php
class ModelLocalisationCurrency extends Model
{

    public function addCurrency($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "currency SET 
        title = '" . $this->db->escape($data['title']) . "'
        , code = '" . $this->db->escape($data['code']) . "'
        , symbol_left = '" . $this->db->escape($data['symbol_left']) . "'
        , symbol_right = '" . $this->db->escape($data['symbol_right']) . "'
        , decimal_place = '" . $this->db->escape($data['decimal_place']) . "'        
        , value = '" . (float)str_replace(",", ".", $data['value']) . "'
        , status = '" . (int)$data['status'] . "', date_modified = NOW()");

        $currency_id = $this->db->getLastId();


        if ($this->config->get('config_currency_auto')) {
            $this->refresh(true);
        }

        $this->cache->delete('currency');

        return $currency_id;
    }

    public function editCurrency($currency_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "currency SET 
        title = '" . $this->db->escape($data['title']) . "'
        , code = '" . $this->db->escape($data['code']) . "'
        , symbol_left = '" . $this->db->escape($data['symbol_left']) . "'
        , symbol_right = '" . $this->db->escape($data['symbol_right']) . "'
        , decimal_place = '" . $this->db->escape($data['decimal_place']) . "'
        , value = '" . (float)str_replace(",", ".", $data['value']) . "'
        , status = '" . (int)$data['status'] . "'
        , date_modified = NOW()
        WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function deleteCurrency($currency_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function getCurrency($currency_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        return $query->row;
    }

    public function getCurrencyByCode($currency)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

    public function getCurrencies($data = [])
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "currency";

            $sort_data = array(
              'title',
              'code',
              'value',
              'date_modified'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $currency_data = $this->cache->get('currency');

            if (!$currency_data) {
                $currency_data = [];

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

                foreach ($query->rows as $result) {
                    $currency_data[$result['code']] = array(
                      'currency_id'   => $result['currency_id'],
                      'title'         => $result['title'],
                      'code'          => $result['code'],
                      'symbol_left'   => $result['symbol_left'],
                      'symbol_right'  => $result['symbol_right'],
                      'decimal_place' => $result['decimal_place'],
                      'value'         => $result['value'],
                      'status'        => $result['status'],
                      'date_modified' => $result['date_modified']
                    );
                }

                $this->cache->set('currency', $currency_data);
            }

            return $currency_data;
        }
    }

    public function refresh($force = false) {
        if ($force) {
		    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
	    } else {
		    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
	    }
        if($query->num_rows == 0) {
            // Already up to date
            return true;
        }

        $zip = new \ZipArchive();
        $ecb_source_url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref.zip';

        if(!file_exists(DIR_STORAGE_PRIVATE . 'download')) {
            mkdir( DIR_STORAGE_PRIVATE . 'download' );
        };

        $path_to_zip = DIR_STORAGE_PRIVATE . 'download/ecb-data.zip';
        $path_to_csv = DIR_STORAGE_PRIVATE . 'download/eurofxref.csv';
        $compressed = file_get_contents($ecb_source_url);
        file_put_contents($path_to_zip, $compressed);
        $res = $zip->open($path_to_zip);
        // Make sure that we downloaded a valid zip archive
        if($res === TRUE) {
            $zip->extractTo(DIR_STORAGE_PRIVATE . 'download/');
            $zip->close();
            $data = file_get_contents($path_to_csv);
            $explode = explode(PHP_EOL, $data);
            $currencies = explode(', ', trim($explode[0], " ,"));
            $values = explode(', ', trim($explode[1], " ,"));
            // Combine currencies and values into a single array
            $combined = array_combine($currencies, $values);

            //Delete uneeded "Date" key
            if(!empty($combined['Date'])){
                unset( $combined['Date'] );
            }

            // Since we're getting values from the European Central Bank and their base currency is the Euro,
            // the Euro should always be present in our array with a value of 1 initially
            $combined['EUR'] = 1;
            // Set the value of the base currrency relative to the Euro, so we can convert the rest of the currencies
            // in the next step.
            $base = $combined[$this->config->get('config_currency')];
            // Now we need to convert the currency values from being relative to the Euro to being relative
            // to our stores base currency:
            $normalized = [];

            if($combined) {
                foreach ($combined as $key => $value) {
                    // This if check is done to drop that value
                    if (!empty($value)) {
                        $normalized[$key] = $value / $base;
                    }
                }
            }
            // Finally, we need to update the currency table with the new values
            foreach ($query->rows as $row) {
                $this->db->query("UPDATE `" . DB_PREFIX . "currency` SET value = " . (float)$normalized[$row['code']] . ", date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($row['code']) . "'");
            }
            $this->cache->delete('currency');
            return true;
        }
    }

    public function getTotalCurrencies()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

        return $query->row['total'];
    }
}
