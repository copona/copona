<?php
class ModelCatalogContent extends Model {

    public function getContentMeta($content_id, $type) {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta 
        WHERE content_id='" . (int)$content_id . "' 
        AND content_type = '" . $this->db->escape($type) . "' LIMIT 1";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            $value = $query->row['value'];
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            // Legacy: fall back to PHP serialized format
            return @unserialize($value) ?: [];
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