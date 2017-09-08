<?php
class ModelCatalogCategory extends Model {

    private $paths = [];
    private $language_id;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->language_id = (int)$registry->config->get('config_language_id');
        $this->paths = $this->paths();
    }

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row;
    }

    public function getCategories($parent_id = 0) {

        $start_time = microtime(true);

        if (!file_exists(DIR_LOGS . 'execdebuglog.txt')) {
            touch(DIR_LOGS . 'execdebuglog.txt');
        }

        $cats = [] ;
        foreach($this->paths as &$path) {
            if($path['parent_id'] == $parent_id) {
                $cats[] = $path;
            }
        }

        $file = fopen(DIR_LOGS . 'execdebuglog.txt', 'a');

        $output = microtime(true) - $start_time;
        fwrite($file, $output . "\n");

        fclose($file);


        return $cats;
        // pr( $cats );

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE c.parent_id = '" . (int)$parent_id . "' 
            AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  
            AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

        //pr(debug_backtrace()[0]['file']);
        //pr(debug_backtrace()[0]['line']);

        return $query->rows;
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
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

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

        if ($category_id) {

            //TODO: return overrided.
            return $this->paths[$category_id]['path'];

            $sql = "SELECT cp.*, cd.name FROM " . DB_PREFIX . "category_path cp
			LEFT JOIN " . DB_PREFIX . "category_description cd
			ON (cp.path_id = cd.category_id)";
            $sql .=" WHERE cp.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cp.level";

            $query = $this->db->query($sql);
            $result = array();
            foreach ($query->rows as $path) {
                $result[] = $path['path_id'];
            }

            return implode("_", $result);
        } else {
            return false;
        }
    }


    private function paths() {


        $cache_key = '123category.paths.' . $this->language_id . '.' . Config::get('config_store_id') . '.' . Config::get('config_customer_group_id');
        $categories = $this->cache->get( $cache_key );
        if($categories) {
            // return $categories;
        }

        // select all categories, already with SEO link
        $language_id = $this->language_id;
        $categories = array();
        $sql = "SELECT 
                  c.category_id as category_id
                  , c.top                  
                  , c2.category_id as parent_id
                  , GROUP_CONCAT(c.category_id ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS path
                  -- , ul.keyword
                  -- , date_format(c.date_modified, '%Y-%m-%d') as date_modified
                  -- , ul2.keyword as keyword_neutral
                  -- , cd.name
					FROM `" . DB_PREFIX . "category` c
					left join " . DB_PREFIX . "category c2 on c.parent_id = c2.category_id					
					left join " . DB_PREFIX . "category_path cp on cp.parent_id = c2.category_id					
					left join " . DB_PREFIX . "url_alias ul on ul.query = concat('category_id=', c.category_id) and c.status = 1  and ul.language_id = '" . $language_id . "'
					
					group by c.parent_id
					
					";

        $sql = "SELECT cp.category_id AS category_id
             , GROUP_CONCAT(IF(c2.parent_id=0, null, c2.parent_id)  ORDER BY cp.level SEPARATOR '_') AS path
            , c1.parent_id
            , c1.top
            , cd.name
            , c1.sort_order 
            FROM " . DB_PREFIX . "category_path cp 
            LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = c1.category_id) and cd.language_id = '".$this->language_id."'
            LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) 
            group by  cp.category_id ";

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

            $categories[$val['category_id']] = $val;
        }

        //pr(1);

        $this->cache->set($cache_key, $categories);
        return $categories;


        //prd( $categories );
        //TODO: must be used for SEO links!
        /*
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
                $link = HTTPS_SERVER . ltrim($link_category_seo, '/');
            } else {
                // At least one Category with broken SEO - so, only non-seo link generated
                $link = HTTPS_SERVER . "?route=product/category&amp;path=" . $path;
            }

            $val['category_link'] = $link;
            $val['fullpath'] = $path;
            $val['category_full'] = trim($category_full);
            $val['broken_seo'] = $broken_seo ? 1 : 0;
        } */

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
					left join " . DB_PREFIX . "category_description cd on cd.category_id = c.category_id AND cd.language_id = '" . $language_id . "'
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
                $link = HTTPS_SERVER . ltrim($link_category_seo, '/');
            } else {
                // At least one Category with broken SEO - so, only non-seo link generated
                $link = HTTPS_SERVER . "?route=product/category&amp;path=" . $path;
            }

            $val['category_link'] = $link;
            $val['fullpath'] = $path;
            $val['category_full'] = trim($category_full);
            $val['broken_seo'] = $broken_seo ? 1 : 0;
        }

        $this->cache->set($cache_key, $categories);
        return $categories;
    }

}