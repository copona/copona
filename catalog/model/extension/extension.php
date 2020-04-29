<?php
class ModelExtensionExtension extends Model {

    private $extensions;

    public function __construct($registry) {
        parent::__construct($registry);
        // $this->language_id = (int)$registry->config->get('config_language_id');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension");

        foreach( $query->rows as $row ) {
            $this->extensions[$row['type']][] = $row;
        }

    }

    function getExtensions($type) {
        // $query = $this->db->query();

        if(!empty($this->extensions[$type])) {
            return $this->extensions[$type];
        } else {
            return [];
        }

    }

}