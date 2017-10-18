<?php

class ControllerProductCategory extends Controller
{


    public function index()
    {

        $data = array_merge(array(), $this->language->load('product/category'));

        $this->load->model('catalog/category');

        $this->load->model('catalog/manufacturer');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $params = $this->url->getParams();

        $params['sort'] = $params['sort'] ? $params['sort'] : Config::get('theme_default_category_sort', 'p.sort_order');

        $params['filter'] = $params['filter'] ? $params['filter'] : '';
        $params['manufacturer_id'] = $params['manufacturer_id'] ? $params['manufacturer_id'] : '';
        $params['order'] = $params['order'] ? $params['order'] : Config::get('theme_default_category_order', 'ASC');
        $params['page'] = $params['page'] ? $params['page'] : 1;
        $params['limit'] = $params['limit'] ? (int)$params['limit'] : $this->config->get($this->config->get('config_theme') . '_product_limit');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $url = '';


        if(empty($this->request->get['path']) && !empty($this->request->get['category_id'])) {
            $this->request->get['path'] = $this->model_catalog_category->getCategoryPath((int)$this->request->get['category_id'], '');
        }

        if (!empty($this->request->get['path'])) {

            $url = $this->url->getPartly(['sort', 'order', 'limit'], true);
            $path = '';
            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);
                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path . $url)
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if (!empty($this->request->get['path']) && $category_info) {
            $show_category = true;
            $category_name = $category_info['name'];
            $category_meta_title = $category_info['meta_title'];
            $category_meta_description = $category_info['meta_description'];
            $category_meta_keyword = $category_info['meta_keyword'];
            $category_path = $this->request->get['path'];
            //	$category_path = $this->model_catalog_category->getCategoryPath($category_id, '');
        } elseif (empty($this->request->get['path']) && !$category_info) {
            $show_category = true;
            $category_name = $this->language->get('text_all_products');

            $category_meta_title = (!empty($this->config->get('theme_default_product_category_meta_title')[$this->config->get('config_language_id')]) ?
                    $this->config->get('theme_default_product_category_meta_title')[$this->config->get('config_language_id')] . ' - ' : '') .
                $this->config->get('config_name');
            $category_meta_description = '';
            $category_meta_keyword = '';
            $category_path = '';
        } else {
            $show_category = false;
        }

        if ($show_category) {
            $this->document->setTitle($category_meta_title);
            $this->document->setDescription($category_meta_description);
            $this->document->setKeywords($category_meta_keyword);
            $data['heading_title'] = $category_name;
            $data['text_compare'] = sprintf($this->language->get('text_compare'),

                (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));


            // Set the last category breadcrumb
            $data['breadcrumbs'][] = array(
                'text' => $category_name,
                'href' => $this->url->link('product/category', 'path=' . $category_path)
            );

            if (isset($category_info['image']) && $category_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($category_info['image'],

                    $this->config->get($this->config->get('config_theme') . '_image_category_width'),
                    $this->config->get($this->config->get('config_theme') . '_image_category_height'));

            } else {
                $data['thumb'] = '';
            }

            $data['description'] = (isset($category_info['description']) && $category_info['description'] ? html_entity_decode($category_info['description'],

                ENT_QUOTES, 'UTF-8') : '');
            $data['compare'] = $this->url->link('product/compare');

            $data['categories'] = array();

            $results = $this->model_catalog_category->getCategories($category_id);

            $url_pattern = $this->url->getPartly(['filter', 'sort', 'order', 'limit']);

            foreach ($results as $result) {

                $filter_data = array(
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true
                );

                $url_pattern['path'] = $category_path . '_' . $result['category_id'];

                $data['categories'][] = array(

                    'name' => $result['name'] . ($this->config->get('config_product_count') ? '&nbsp;(' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))
                );
            }

            $data['products'] = array();


            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_sub_category' => true,
                'filter_filter' => $params['filter'],
                'filter_manufacturer_id' => $params['manufacturer_id'],
                'sort' => $params['sort'],
                'order' => $params['order'],
                'start' => ($params['page'] - 1) * $params['limit'],
                'limit' => $params['limit']
            );

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {

                if ($result['image']) {
                    $image = $this->model_tool_image->{$this->config->get('theme_default_product_category_list_resize')}($result['image'],

                        $this->config->get($this->config->get('config_theme') . '_image_product_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_product_height'));

                    $image_popup = $this->model_tool_image->{$this->config->get('theme_default_product_category_popup_resize')}($result['image'],
                        $this->config->get($this->config->get('config_theme') . '_image_popup_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                } else {
                    $image = $this->model_tool_image->{$this->config->get('theme_default_product_category_list_resize')}('no_image.png',
                        $this->config->get($this->config->get('config_theme') . '_image_product_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                    $image_popup = $this->model_tool_image->{$this->config->get('theme_default_product_category_popup_resize')}('no_image.png',
                        $this->config->get($this->config->get('config_theme') . '_image_popup_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                }

                // pr($image);

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'],

                        $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'],

                        $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'],

                        $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $category_path = $this->model_catalog_category->getCategoryPath($category_id, $result['product_id']);
                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'popup' => $image_popup,
                    'name' => $result['name'],
                    'description' => strip2words($result['description'],
                            $this->config->get($this->config->get('config_theme') . '_product_description_length'),
                            true) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],
                    'quantity' => $result['quantity'],
                    'href' => $this->url->link('product/product',
                        ($category_path ? 'path=' . $category_path . '&' : '') . 'product_id=' . $result['product_id'] . $url),
                    'group_products' => $this->model_catalog_product->getProducts(
                        [
                            'group_products' => true,
                            'product_group_id' => $result['product_group_id'],
                            'product_id' => $result['product_id']
                        ]
                    ),
                );
            }

            $data['sorts'] = array();

            // by Sort Order
            $url_pattern = $this->url->getPartly(['path', 'filter', 'sort', 'order', 'limit']);
            $url_pattern['path'] = $category_path;
            $url_pattern['sort'] = 'p.sort_order';
            $url_pattern['order'] = 'ASC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            // by Name ASC
            $url_pattern['sort'] = 'pd.name';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            // by Name DESC
            $url_pattern['order'] = 'DESC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            // by Price ASC
            $url_pattern['sort'] = 'p.price';
            $url_pattern['order'] = 'ASC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            // by Price DESC
            $url_pattern['order'] = 'DESC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            if ($this->config->get('config_review_status')) {

                // by Review Status DESC
                $url_pattern['sort'] = 'rating';
                $url_pattern['order'] = 'DESC';

                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

                );

                // by Review Status ASC
                $url_pattern['order'] = 'ASC';

                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))
                );
            }

            // by Model ASC
            $url_pattern['sort'] = 'model';
            $url_pattern['order'] = 'ASC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

            );

            // by Model DESC
            $url_pattern['order'] = 'DESC';

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))
            );

            $url_pattern = $this->url->getPartly(['filter', 'sort', 'order']);
            $url_pattern['path'] = $category_path;

            $data['limits'] = array();

            $limits = array_unique(array(
                $this->config->get($this->config->get('config_theme') . '_product_limit'),
                25,
                50,
                75,
                100

            ));

            sort($limits);

            foreach ($limits as $value) {
                $url_pattern['limit'] = $value;
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern))

                );
            }

            $url_pattern = $this->url->getPartly(['path', 'filter', 'sort', 'order', 'limit']);

            // Manufacturers
            // $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
            // TODO: able to configure "show manufacturers in category" in admin! For performance.

            $manufacturers = $this->model_catalog_manufacturer->getManufacturersByCategory($category_id);
            $data['manufacturers'] = [];

            foreach ($manufacturers as $manufacturer) {
                $image = $this->model_tool_image->{$this->config->get('theme_default_product_category_list_resize')}($manufacturer['image'],
                    $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
                    $this->config->get($this->config->get('config_theme') . '_image_additional_height'));

                $url_pattern['manufacturer_id'] = $manufacturer['manufacturer_id'];
                $data['manufacturers'][] = [
                    'manufacturer_id' => $manufacturer['manufacturer_id'],
                    'name' => $manufacturer['name'],
                    'image' => $image,
                    'href' => $this->url->link('product/category', $this->url->setRequest($url_pattern)),
                ];
            }

            $url_pattern = $this->url->getPartly(['filter', 'manufacturer_id', 'sort', 'order', 'limit']);
            $url_pattern['page'] = "{page}";
            $url_pattern['path'] = $category_info ? $category_path : '';

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $params['page'];
            $pagination->limit = $params['limit'];
            $pagination->text_first = '';
            $pagination->text_last = '';
            $pagination->prev_hide = $this->config->get('theme_default_pagination_prev_hide') === null ? false : $this->config->get('theme_default_pagination_prev_hide');
            $pagination->next_hide = $this->config->get('theme_default_pagination_next_hide') === null ? false : $this->config->get('theme_default_pagination_next_hide');
            $pagination->url = $this->url->link('product/category', $this->url->setRequest($url_pattern));


            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'),
                ($product_total) ? (($params['page'] - 1) * $params['limit']) + 1 : 0,
                ((($params['page'] - 1) * $params['limit']) > ($product_total - $params['limit'])) ? $product_total : ((($params['page'] - 1) * $params['limit']) + $params['limit']),
                $product_total, ceil($product_total / $params['limit']));


            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if ($params['page'] == 1) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_id), 'canonical');
            } elseif ($params['page'] == 2) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_id), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/category',
                    'path=' . $category_id . '&page=' . ($params['page'] - 1)), 'prev');
            }

            if ($params['limit'] && ceil($product_total / $params['limit']) > $params['page']) {
                $this->document->addLink($this->url->link('product/category',
                    'path=' . $category_id . '&page=' . ($params['page'] + 1)), 'next');

            }


            $data['sort'] = $params['sort'];
            $data['order'] = $params['order'];
            $data['limit'] = $params['limit'];

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $this->hook->getHook('category/index/after', $data);
            $this->response->setOutput($this->load->view('product/category', $data));
        }

        if (!empty($this->request->get['path']) && !$category_info) {

            $url = $this->url->getPartly(['path', 'filter', 'sort', 'order', 'page', 'limit'], true);

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/category', $url)
            );

            $data['heading_title'] = $this->language->get('text_error');
            $this->document->setTitle($this->language->get('text_error'));
            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

}