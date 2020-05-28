<?php
class ModelCatalogContent extends Model {

    public function getContentMeta($content_id, $type) {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta 
        WHERE content_id='" . (int)$content_id . "' 
        AND content_type = '" . $this->db->escape($type) . "' LIMIT 1";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            // Compatibility: Check if serialized at first, then - json_decode:
            // https://stackoverflow.com/a/1369946/1720476
            // TODO: leave only JSON!
            $data = @unserialize($query->row['value']);
            if ($query->row['value'] === 'b:0;' || $data !== false) {
                return unserialize($query->row['value']);
            } else {
                return json_decode($query->row['value'], 1);
            }
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