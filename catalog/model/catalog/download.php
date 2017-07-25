<?php
class ModelCatalogDownload extends Model {

    public function getFreeDownload($id){
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_download` ptd
                            JOIN `cp_download` d
                            On d.download_id = ptd.download_id
                            JOIN `cp_download_description` dd
                            On dd.download_id = ptd.download_id
                            where dd.language_id = " . (int)$this->config->get('config_language_id')." AND dd.download_id=".$id." AND is_free=1");
        return $query->row;
    }
}