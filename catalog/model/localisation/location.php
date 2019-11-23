<?php
class ModelLocalisationLocation extends Model {

    public function getLocation($location_id) {
        $query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

        return $query->row;
    }

    /*
     * TODO: Should be moved to CART, shouldn't?
     */
    public function getStoreAddress($store_id = 0 ) {
        // $query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

        //prd($this);

        $result['country_id'] = $this->config->get('config_country_id');
        $result['zone_id'] = $this->config->get('config_zone_id');
        return $result;
    }


}