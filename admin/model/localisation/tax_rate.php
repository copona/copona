<?php
class ModelLocalisationTaxRate extends Model {

    private function deriveNameFromDescriptions($data) {
        if (!empty($data['name'])) {
            return $data['name'];
        }
        if (!empty($data['tax_rate_description'])) {
            $default_lang = (int)$this->config->get('config_language_id');
            if (isset($data['tax_rate_description'][$default_lang]['name'])) {
                return $data['tax_rate_description'][$default_lang]['name'];
            }
            $first = reset($data['tax_rate_description']);
            return $first['name'] ?? '';
        }
        return '';
    }

    public function addTaxRate($data) {
        $name = $this->deriveNameFromDescriptions($data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($name) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_added = NOW(), date_modified = NOW()");

        $tax_rate_id = $this->db->getLastId();

        if (isset($data['tax_rate_customer_group'])) {
            foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
            }
        }

        if (isset($data['tax_rate_description'])) {
            foreach ($data['tax_rate_description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_description SET tax_rate_id = '" . (int)$tax_rate_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
            }
        }

        return $tax_rate_id;
    }

    public function editTaxRate($tax_rate_id, $data) {
        $name = $this->deriveNameFromDescriptions($data);

        $this->db->query("UPDATE " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($name) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_modified = NOW() WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        if (isset($data['tax_rate_customer_group'])) {
            foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_description WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        if (isset($data['tax_rate_description'])) {
            foreach ($data['tax_rate_description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_description SET tax_rate_id = '" . (int)$tax_rate_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
            }
        }
    }

    public function deleteTaxRate($tax_rate_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_description WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
    }

    public function getTaxRate($tax_rate_id) {
        $query = $this->db->query("SELECT tr.tax_rate_id, COALESCE(trd.name, tr.name) AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "tax_rate_description trd ON (tr.tax_rate_id = trd.tax_rate_id AND trd.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = '" . (int)$tax_rate_id . "'");

        return $query->row;
    }

    public function getTaxRateDescriptions($tax_rate_id) {
        $data = [];

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate_description WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        foreach ($query->rows as $result) {
            $data[$result['language_id']] = ['name' => $result['name']];
        }

        return $data;
    }

    public function getTaxRates($data = array()) {
        $sql = "SELECT tr.tax_rate_id, COALESCE(trd.name, tr.name) AS name, tr.rate, tr.type, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "tax_rate_description trd ON (tr.tax_rate_id = trd.tax_rate_id AND trd.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id)";

        $sort_data = array(
            'tr.name',
            'tr.rate',
            'tr.type',
            'gz.name',
            'tr.date_added',
            'tr.date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY tr.name";
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
    }

    public function getTaxRateCustomerGroups($tax_rate_id) {
        $tax_customer_group_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        foreach ($query->rows as $result) {
            $tax_customer_group_data[] = $result['customer_group_id'];
        }

        return $tax_customer_group_data;
    }

    public function getTotalTaxRates() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate");

        return $query->row['total'];
    }

    public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        return $query->row['total'];
    }

}
