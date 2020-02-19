<?php

class ControllerInformationInformation extends Controller {

    public function index() {
        $this->load->language('information/information');

        $this->load->model('catalog/information');
        $this->load->model('tool/image');
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        if (isset($this->request->get['information_id'])) {
            $information_id = (int)$this->request->get['information_id'];
        } else {
            $information_id = 0;
        }

        $information_info = $this->model_catalog_information->getInformation($information_id);

        if ($information_info) {

            if (isset($information_info['external_link']) && $information_info['external_link']) {
                $this->response->redirect($this->url->externalLink($information_info['external_link']));
            }


            if (empty($information_info['meta_title'])) {
                $information_info['meta_title'] = strip2words($information_info['title']
                    . " - " . Config::get('config_meta_title')
                    . " | " . Config::get('config_name'), 300);
            }

            if (empty($information_info['meta_description'])) {
                $information_info['meta_description'] = strip2words($information_info['description'] . " | " . $information_info['title'], 200);
            }

            $this->document->setTitle($information_info['meta_title']);
            $this->document->setDescription($information_info['meta_description']);

            $this->document->setKeywords($information_info['meta_keyword']);
            $this->document->addScript('assets/vendor/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('assets/vendor/magnific/magnific-popup.css');

            $data['breadcrumbs'][] = [
                'text' => $information_info['title'],
                'href' => $this->url->link('information/information', 'information_id=' . $information_id),
            ];

            $data['heading_title'] = $information_info['title'];

            $data['button_continue'] = $this->language->get('button_continue');

            $data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

            if ($information_info['image']) {
                $data['popup'] = $this->model_tool_image->{Config::get('theme_default_information_image_resize', 'resize')}($information_info['image'],
                    Config::get(Config::get('config_theme') . '_information_image_popup_width', 800),
                    Config::get(Config::get('config_theme') . '_information_image_popup_height', 400));
            } else {
                $data['popup'] = '';
            }

            if ($information_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($information_info['image'],
                    Config::get(Config::get('config_theme') . '_image_thumb_width'),
                    Config::get(Config::get('config_theme') . '_image_thumb_height'));
            } else {
                $data['thumb'] = '';
            }

            $data['images'] = [];

            $results = $this->model_catalog_information->getInformationImages($this->request->get['information_id']);

            foreach ($results as $result) {
                $data['images'][] = [
                    'popup' => $this->model_tool_image->cropsize($result['image'],
                        Config::get(Config::get('config_theme') . '_image_popup_width'),
                        Config::get(Config::get('config_theme') . '_image_popup_height')),
                    'thumb' => $this->model_tool_image->cropsize($result['image'], Config::get(Config::get('config_theme') . '_image_additional_width'),
                        Config::get(Config::get('config_theme') . '_image_additional_height')),
                ];
            }

            $data['continue'] = $this->url->link('common/home');

            $this->hook->getHook('information/information/index/after', $data);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');


            $this->response->setOutput($this->load->view('information/information', $data));
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('information/information', 'information_id=' . $information_id),
            ];

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

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

    public function agree() {
        $this->load->model('catalog/information');

        if (isset($this->request->get['information_id'])) {
            $information_id = (int)$this->request->get['information_id'];
        } else {
            $information_id = 0;
        }

        $output = '';

        $information_info = $this->model_catalog_information->getInformation($information_id);

        if ($information_info) {
            $output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
        }

        $this->response->setOutput($output);
    }

}