<?php
class ModelLocalisationCountry extends Model {

    public function getCountry($country_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

        return $query->row;
    }

    public function getCountries() {
        $country_data = $this->cache->get('country.catalog');

        if (!$country_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");

            foreach( $query->rows as $row) {
                $country_data[$row['country_id']] = $row;
            }

            $this->cache->set('country.catalog', $country_data);
        }

        return $country_data;
    }

}
