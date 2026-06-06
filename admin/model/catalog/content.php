<?php
class ModelCatalogContent extends Model {

    public function getContentMeta($content_id, $type) {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta 
        WHERE content_id='" . (int)$content_id . "' 
        AND content_type = '" . $this->db->escape($type) . "' LIMIT 1";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            $value = $query->row['value'];
            // Try JSON first (all new data is JSON); fall back to legacy PHP serialize
            $json = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
            $data = @unserialize($value);
            return ($value === 'b:0;' || $data !== false) ? $data : [];
        } else {
            return [];
        }
    }


    /*
     * We can easily "replace" content meta, because updating requires to select first ALL, then replace with specific.
     * */

    public function updateContentMeta($content_id, $type, $value) {
        $sql = "REPLACE INTO " . DB_PREFIX . "content_meta SET "
               . "value = '" . $this->db->escape(json_encode($value, JSON_UNESCAPED_UNICODE)) . "' "
               . ", content_type = '" . $type . "', content_id = " . (int)$content_id;
        $this->db->query($sql);

    }

}