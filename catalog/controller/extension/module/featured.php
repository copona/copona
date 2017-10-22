<?php
class ControllerExtensionModuleFeatured extends Controller {

    public function index($setting) {

        $data = $this->load->language('extension/module/featured');

        if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
            $data['heading_title'] = $setting['module_description'][$this->config->get('config_language_id')]['title'];
        };

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['products'] = array();

        if (!$setting['limit']) {
            $setting['limit'] = 4;
        }

        if (!empty($setting['product'])) {
            $count = 0;
            foreach ($setting['product'] as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if(++$count > $setting['limit'])
                        break;
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->{$this->config->get('theme_default_extension_module_featured')}($product_info['image'], $setting['width'], $setting['height']);
                    } else {
                        $image = $this->model_tool_image->{$this->config->get('theme_default_extension_module_featured')}('placeholder.png', $setting['width'], $setting['height']);
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

                    if ((float)$product_info['special']) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = $product_info['rating'];
                    } else {
                        $rating = false;
                    }

                    $data['products'][] = array(
                        'product_id'    => $product_info['product_id'],
                        'minimum'       => $product_info['minimum'] == 0 ? 1 : $product_info['minimum'] ,
                        'thumb'         => $image,
                        'name'          => $product_info['name'],
                        'description'   => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'         => $price,
                        'special'       => $special,
                        'tax'           => $tax,
                        'rating'        => $rating,
                        'content_meta'  => $product_info['content_meta'],
                        'href'          => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
            }
        }

        if ($data['products']) {
            if(!empty($setting['content_data']) && $setting['content_data']){
                return $data;
            } else {
                return $this->load->view('extension/module/featured', $data);
            }
        }
    }

}