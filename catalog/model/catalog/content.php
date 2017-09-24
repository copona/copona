<?php
class ModelCatalogContent extends Model {

    public function getContentMeta($content_id, $type) {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta WHERE content_id='" . $content_id . "' AND content_type = '" . $type . "'";
        $query = $this->db->query($sql);

        if ($query->row) {
            return unserialize($query->row['value']);
        } else {
            return [ ];
        }
    }

    public function getAllContentMeta($type) {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta WHERE  content_type = '" . $type . "'";
        $query = $this->db->query($sql);

        $data = [];

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $data[$row['content_id']] = [
                    'id'           => $row['content_id'],
                    'content_type' => $row['content_type'],
                    'content_id'   => $row['content_id'],
                    'value'        => unserialize($row['value'])
                ];
            }
        }

         return $data;

    }

    public function updateContentMeta($content_id, $type, $value) {
        //pr($value);
        $sql = "SELECT * from " . DB_PREFIX . "content_meta WHERE content_id='" . $content_id . "' AND content_type = '" . $type . "'";
        $query = $this->db->query($sql);
        //pr($sql);
        //pr($this->db->escape(serialize($value)));
        if ($query->row) {
            $id = $query->row['id'];
            $sql = "UPDATE " . DB_PREFIX . "content_meta SET "
                . "value = '" . $this->db->escape(serialize($value)) . "' "
                . "where content_type = '" . $type . "' AND id = '" . $id . "'";
            $query = $this->db->query($sql);
        } else {
            $this->addContentMeta($content_id, $type, $value);
        }
        //prd($sql);
    }

    private function addContentMeta($content_id, $type, $value) {
        $sql = "INSERT INTO " . DB_PREFIX . "content_meta SET content_type = '" . $type . "' , "
            . "content_id = '" . (int)$content_id . "', "
            . "value = '" . $this->db->escape(serialize($value)) . "'";
        $this->db->query($sql);
    }

}