<?php
class ControllerExtensionModuleLatest extends Controller {

    public function index($setting) {
        $this->load->language('extension/module/latest');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['products'] = array();

        $filter_data = array(
            'sort'  => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->{Config::get('theme_default_latest_thumb_resize')}($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->{Config::get('theme_default_latest_thumb_resize')}('placeholder.png', $setting['width'], $setting['height']);
                }
                if ($result['image']) {
                    $popup = $this->model_tool_image->{Config::get('theme_default_latest_thumb_resize')}($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                } else {
                    $popup = $this->model_tool_image->{Config::get('theme_default_latest_thumb_resize')}('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }


                $group_products_data = array();
                if ($result['product_group_id']) {

                    $filter = array(
                        'group_products'   => true,
                        'product_group_id' => $result['product_group_id'],
                        'product_id'       => $result['product_id']
                    );
                    $group_products = $this->model_catalog_product->getProducts($filter);
                    foreach ($group_products as $group_product) {
                        if ($group_product['product_id'] == $result['product_id'])
                            continue;

                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $group_price = $this->currency->format($this->tax->calculate($group_product['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $group_price = false;
                        }

                        if ($group_product['image']) {
                            $group_image = $this->model_tool_image->cropsize($group_product['image'], $this->config->get($this->config->get('config_theme') . '_image_product_group_width'), $this->config->get($this->config->get('config_theme') . '_image_product_group_height'));
                        } else {
                            $group_image = $this->model_tool_image->cropsize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_group_width'), $this->config->get($this->config->get('config_theme') . '_image_product_group_height'));
                        }

                        $group_products_data[] = array(
                            'product_id'       => $group_product['product_id'],
                            'product_group_id' => $group_product['product_group_id'],
                            'name'             => $group_product['name'],
                            'description'      => html_entity_decode($group_product['description'], ENT_QUOTES, 'UTF-8'),
                            'product_price'    => $group_product['price'],
                            'image'            => $group_image,
                            'price'            => $group_price,
                            'href'             => $this->url->link('product/product', 'product_id=' . $group_product['product_id'])
                        );
                    }
                }
                $data['products'][] = array(
                    'product_id'           => $result['product_id'],
                    'thumb'                => $image,
                    'popup'                => $popup,
                    'name'                 => $result['name'],
                    'stripped_description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'description'          => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
                    'price'                => $price,
                    'product_price'        => $result['price'],
                    'special'              => $special,
                    'tax'                  => $tax,
                    'sku'                  => $result['sku'],
                    'rating'               => $rating,
                    'href'                 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    'group_products'       => $group_products_data
                );
            }

            return $this->load->view('extension/module/latest', $data);
        }
    }

}