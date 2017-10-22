<?php

class ModelCatalogContent extends Model
{

    public function getContentMeta($content_id, $type)
    {
        $sql = "SELECT * from " . DB_PREFIX . "content_meta WHERE content_id='" . (int)$content_id . "' AND content_type = '" . $this->db->escape($type) . "'";
        $query = $this->db->query($sql);

        if ($query->row) {
            return unserialize($query->row['value']);
        } else {
            return [];
        }
    }

    public function updateContentMeta($content_id, $type, $value)
    {
        //pr($value);
        $sql = "SELECT * from " . DB_PREFIX . "content_meta WHERE content_id='" . (int)$content_id . "' AND content_type = '" . $this->db->escape($type) . "'";
        $query = $this->db->query($sql);
        //pr($sql);
        //pr($this->db->escape(serialize($value)));
        if ($query->row) {
            $id = $query->row['id'];
            $sql = "UPDATE " . DB_PREFIX . "content_meta SET "
                . "value = '" . $this->db->escape(serialize($value)) . "' "
                . "where content_type = '" . $this->db->escape($type) . "' AND id = '" . $id . "'";
            $query = $this->db->query($sql);
        } else {
            $this->addContentMeta($content_id, $type, $value);
        }
        //prd($sql);
    }

    private function addContentMeta($content_id, $type, $value)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "content_meta SET content_type = '" . $this->db->escape($type) . "' , "
            . "content_id = '" . (int)$content_id . "', "
            . "value = '" . $this->db->escape(serialize($value)) . "'";
        $this->db->query($sql);
    }

}