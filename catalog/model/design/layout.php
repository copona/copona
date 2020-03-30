<?php
class ModelDesignLayout extends Model {

    private $layouts;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->language_id = (int)$registry->config->get('config_language_id');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module 
        ORDER BY sort_order ASC ");

        foreach( $query->rows as $row ) {
            $this->layouts[$row['layout_id'] . $row['position'] ][] = $row;
        }

    }


    public function getLayout($route) {


        $sql = "SELECT * FROM " . DB_PREFIX . "layout_route 
        WHERE true 
        AND route LIKE '" . $this->db->escape($route) . "'  
        AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1";

        $cache_key = 'layout.route.' . md5($sql);

        $return = $this->cache->get($cache_key);
        if ($return === null) {

            $query = $this->db->query($sql);


            if ($query->num_rows) {
                $return = $query->row['layout_id'];
            } else {
                $return = 0;
            }

            $this->cache->set($cache_key,$return);
            return $return;
        }
//        prd();
        return $return;

    }

    public function getLayoutModules($layout_id, $position) {



        if(!empty($this->layouts[$layout_id . $position ])) {
            return $this->layouts[$layout_id . $position ];
        } else {
            return [] ;
        }

    }

}