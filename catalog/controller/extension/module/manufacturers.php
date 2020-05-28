<?php

class ControllerExtensionModuleManufacturers extends Controller {

    public function index($setting) {
        $data = $this->load->language('extension/module/manufacturers');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

        $this->load->model('tool/image');

        $data['products'] = [];
        $data['module'] = $data['module_id'] = $setting['module_id'];


        $results = $this->model_catalog_manufacturer->getManufacturers();


        if ($results) {

            foreach ($results as $result) {
                // if (is_numeric(utf8_substr($result['name'], 0, 1))) {
                //     $key = '0 - 9';
                // } else {
                //     $key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
                // }
                //
                // if (!isset($data['categories'][$key])) {
                //     $data['categories'][$key]['name'] = $key;
                // }

                //$data['categories'][$key]['manufacturer'][] = array(

                if ($result['keyword']) {
                    $link = HTTP_SERVER . Config::get('code') . "/" . ltrim($result['keyword'], '/');
                } else {
                    $link = HTTP_SERVER . Config::get('code') . "/?" . "route=product/manufacturer/info&manufacturer_id={$result['manufacturer_id']}";
                }


                $data['manufacturers'][] = [
                    'name'  => $result['name'],
                    //'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
                    'href'  => $link,
                    'image' => $this->model_tool_image->{Config::get('theme_default_manufacturers_thumb_resize')}($result['image'],
                        $setting['width'],
                        $setting['height']
                    ),

                ];
            }


            $data['template'] = 'extension/module/manufacturers';

            // prd( $this->load->view($data['template'], $data));


            $this->hook->getHook('extension/module/manufacturers/after', $data);

            if ($data['manufacturers']) {
                if (!empty($setting['content_data']) && $setting['content_data']) {
                    return $data;
                } else {
                    return $this->load->view($data['template'], $data);
                }
            }
        }

    }
}