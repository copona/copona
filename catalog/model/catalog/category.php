<?php
class ModelCatalogCategory extends Model {

    private $paths = [];
    private $language_id;
    // TODO: shoud be moved globally.
    private $code;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->language_id = (int)$registry->config->get('config_language_id');
        $this->paths = $this->paths();
        //TODO: should be moved globally
        $this->code = Config::get('code') ? Config::get('code') . "/" : '';
    }

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE c.category_id = '" . (int)$category_id . "' 
            AND cd.language_id = '" . (int)$this->language_id . "' 
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
            AND c.status = '1'");
        //$seolink = $this->getCategorySeoLink( $category_id );
        return $query->row;
    }

    public function getCategories($parent_id = 0) {

        $start_time = microtime(true);

        if (!file_exists(DIR_LOGS . 'execdebuglog.txt') && DEBUG_MODE) {
            touch(DIR_LOGS . 'execdebuglog.txt');
        }

        $cats = [] ;
        foreach(explode(',', $this->paths[$parent_id]['childrens']) as $category_id) {
            if(!empty($this->paths[$category_id]['path'])) {
                $cats[] = $this->paths[$category_id];
            }
        }

        // Correct categories sorting
        array_multisort(array_column($cats, 'sort_order'),  SORT_ASC,
            array_column($cats, 'name'), SORT_ASC, $cats);


        if(Config::get('debug.mode')) {
            $file = fopen(DIR_LOGS . 'execdebuglog.txt', 'a');
            $output = microtime(true) - $start_time;
            fwrite($file, "Start: for parent $parent_id : ". $output . "\n");
            fclose($file);
        }


        return $cats;

        /*
         * OLD select, just for to check sure
         *
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE c.parent_id = '" . (int)$parent_id . "' 
            AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  
            AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
        return $query->rows;
        */
    }

    public function getCategoryFilters($category_id) {
        $implode = array();

        $query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $implode[] = (int)$result['filter_id'];
        }

        $filter_group_data = array();

        if ($implode) {
            $filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

            foreach ($filter_group_query->rows as $filter_group) {
                $filter_data = array();

                $filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

                foreach ($filter_query->rows as $filter) {
                    $filter_data[] = array(
                        'filter_id' => $filter['filter_id'],
                        'name'      => $filter['name']
                    );
                }

                if ($filter_data) {
                    $filter_group_data[] = array(
                        'filter_group_id' => $filter_group['filter_group_id'],
                        'name'            => $filter_group['name'],
                        'filter'          => $filter_data
                    );
                }
            }
        }

        return $filter_group_data;
    }

    public function getCategoryLayoutId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getTotalCategoriesByCategoryId($parent_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c 
        LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
        WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id')
          . "' AND c.status = '1'");

        return $query->row['total'];
    }

    public function getCategoryPath($category_id = 0, $product_id = 0) {

        if (isset($product_id) && $product_id && !$category_id) {
            //TODO: is this used?
            $sql = "SELECT category_id FROM " . DB_PREFIX . "product_to_category
		  WHERE product_id = '" . $product_id . "' GROUP BY category_id";
            $query = $this->db->query($sql);
            $category_id = (isset($query->row['category_id']) ? $query->row['category_id'] : '' );
        }

        if ($category_id && !empty($this->paths[$category_id]['path'])) {
            //TODO: return overrided.
            return $this->paths[$category_id]['path'];

        }
    }


    private function paths() {
        $cache_key = 'category.paths.' . $this->language_id . '.' . Config::get('config_store_id') . '.' . Config::get('config_customer_group_id');
        $categories = $this->cache->get( $cache_key );
        if($categories) {
            return $categories;
        }
        // select all categories, already with SEO link
        $categories = [];
        $sql = "SELECT cp.category_id AS category_id
            , IFNULL(concat(GROUP_CONCAT(IF(c2.parent_id=0, null, c2.parent_id)  ORDER BY cp.level SEPARATOR '_'),'_', cp.category_id), c1.category_id) AS path
            , c1.parent_id
            , c1.top
            , c1.column
            , cd.name
            , c1.sort_order
            , ul.keyword 
            , (select GROUP_CONCAT(category_id SEPARATOR ',') from " . DB_PREFIX . "category where parent_id = cp.category_id) as childrens 
            FROM " . DB_PREFIX . "category_path cp 
            LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = c1.category_id) and cd.language_id = '" . $this->language_id . "'
            LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id)
            LEFT JOIN " . DB_PREFIX . "url_alias ul on ul.query = concat('category_id=', cp.category_id) and ul.language_id = '" . $this->language_id . "'
            WHERE c1.status = 1 
            group by  cp.category_id ";

        $result = $this->db->query($sql);
        // Only categories array with keys

        $categories[0]['childrens'] = '';

        foreach ($result->rows as $val) {
            // skip, if "parent to self" by mistake.
            if ($val['parent_id'] == $val['category_id']) {
                $val['parent_id'] = 0;
            }

            if (!$val['parent_id']) {
                $val['parent_id'] = 0;
                $categories[0]['childrens'] = $categories[0]['childrens'] .  $val['category_id'] .  ",";
            }
            $categories[$val['category_id']] = $val;
        }
        $this->cache->set($cache_key, $categories);
        return $categories;
    }

    /*
     * TODO: Finish him! Not USED anywhere yet! :)
     */
    private function getAllCategories() {


        $cache_key = 'category.getall.' . $this->language_id . '.' . Config::get('config_store_id') . '.' . Config::get('config_customer_group_id');
        $categories = $this->cache->get( $cache_key );
        if($categories) {
            return $categories;
        }


        // select all categories, already with SEO link
        $language_id = $this->language_id;
        $categories = array();
        $sql = "SELECT c.category_id as category_id, c2.category_id as parent_id, ul.keyword,
                    date_format(c.date_modified, '%Y-%m-%d') as date_modified,
                    ul2.keyword as keyword_neutral, cd.name
					FROM `" . DB_PREFIX . "category` c
					left join " . DB_PREFIX . "category c2 on c.parent_id = c2.category_id
					join " . DB_PREFIX . "category_description cd on cd.category_id = c.category_id AND cd.language_id = '" . $language_id . "'
					left join " . DB_PREFIX . "url_alias ul on ul.query = concat('category_id=', c.category_id) and c.status = 1  and ul.language_id = '" . $language_id . "'
					left join " . DB_PREFIX . "url_alias ul2 on ul2.query = concat('category_id=', c.category_id)  and ul2.language_id = '0'";


        $result = $this->db->query($sql);
        // Only categories array with keys
        foreach ($result->rows as $val) {
            // skip, if "parent to self" by mistake.
            if ($val['parent_id'] == $val['category_id']) {
                $val['parent_id'] = 0;
            }

            if (!$val['parent_id']) {
                $val['parent_id'] = 0;
            }

            $val['name'] = htmlspecialchars(trim($val['name']));

            $categories[$val['category_id']] = $val;
        }

        foreach ($categories as &$val) {

            // building full category link
            $i = 0;
            $link_category_seo = '';
            $path = '';
            $broken_seo = false;
            $category_full = '';

            $category_id = $val['category_id'];

            //TODO: this is "including" default keyword,
            // as Copona is only multi-lingual, this would not be necessary.
            while ($category_id > 0 && $i < 10) {
                if (isset($categories[$category_id]['keyword']) && $categories[$category_id]['keyword']) {
                    $link_category_seo = "/" . $categories[$category_id]['keyword'] . $link_category_seo;
                } elseif (isset($categories[$category_id]['keyword_neutral']) && $categories[$category_id]['keyword_neutral']) {
                    $link_category_seo = "/" . $categories[$category_id]['keyword_neutral'] . $link_category_seo;
                } else {
                    $broken_seo = true;
                }

                // we need to build non-seo link, to use, if at least one category will be without SEO
                $path = $category_id . "_" . $path;
                if ($category_full) {
                    $category_full = $categories[$category_id]['name'] . " &gt;&gt; " . $category_full;
                } else {
                    $category_full = $categories[$category_id]['name'];
                }

                if (isset($categories[$category_id]['parent_id'])) {
                    $category_id = $categories[$category_id]['parent_id'];
                } else {
                    break;
                }

                $i++;
            }


            $path = trim($path, "_");

            // if there is at least on "broken seo"
            // We build full non-seo link

            if (!$broken_seo) {
                // All categories have SEO link
                $link = HTTP_SERVER . $this->code . ltrim($link_category_seo, '/');
            } else {
                // At least one Category with broken SEO - so, only non-seo link generated
                $link = HTTP_SERVER . "?route=product/category&amp;path=" . $path;
            }

            $val['category_link'] = $link;
            $val['fullpath'] = $path;
            $val['category_full'] = trim($category_full);
            $val['broken_seo'] = $broken_seo ? 1 : 0;
        }

        $this->cache->set($cache_key, $categories);
        return $categories;
    }

    /*
     * int $category_id
     * bool $server - return with full pre-link, or only category path.
     *
     */
    public function getCategorySeoLink($category_id, $server = true ) {

            // building full category link
            $i = 0;
            $link_category_seo = '';
            $path = '';
            $broken_seo = false;
            //$category_full = '';
            while ($category_id > 0 && $i < 10) {
                if (!empty($this->paths[$category_id]['keyword'])) {
                    $link_category_seo = "/" . $this->paths[$category_id]['keyword'] . $link_category_seo;
                } else {
                    $broken_seo = true;
                }

                // we need to build non-seo link, to use, if at least one category will be without SEO
                $path = $category_id . "_" . $path;
                /*
                if ($category_full) {
                    $category_full = $this->paths[$category_id]['name'] . " &gt;&gt; " . $category_full;
                } else {
                    $category_full = $this->paths[$category_id]['name'];
                }
                */

                if (!empty($this->paths[$category_id]['parent_id'])) {
                    $category_id = $this->paths[$category_id]['parent_id'];
                } else {
                    break;
                }

                $i++;
            }

            $path = trim($path, "_");

            // if there is at least on "broken seo"
            // We build full non-seo link
            if (!$broken_seo && $this->config->get('config_seo_url')) {
                // All categories have SEO link
                if(!$server) {
                    // TODO: workaround only for seo_url
                    // used in seo_url contorller
                    $link = $link_category_seo;
                } else {
                    $link = HTTP_SERVER . $this->code . ltrim($link_category_seo, '/');
                }

            } else {
                // At least one Category with broken SEO - so, only non-seo link generated
                if(!$server) {
                    //TODO: workaround only for seo_url
                    $link = '';
                } else {
                    $link = HTTP_SERVER . "?route=product/category&amp;path=" . $path;

                }

            }

            $val['category_link'] = $link;
            $val['fullpath'] = $path;
            return $link;

    }

}