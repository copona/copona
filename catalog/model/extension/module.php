<?php
class ModelExtensionModule extends Model {

    public function getModule($module_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");

        if ($query->row) {
            $result = json_decode($query->row['setting'], true);
            $result['module_id'] = $module_id;
            return $result;
        } else {
            return array();
        }
    }

}