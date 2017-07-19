<?php
class ControllerExtensionModuleSlideshow extends Controller {

    public function index($setting) {
        static $module = 0;

        $this->load->model('design/banner');
        $this->load->model('tool/image');

        $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/vendor/owl-carousel/owl.carousel.css');
        $this->document->addScript('themes/' . $this->config->get('theme_name') . '/assets/vendor/owl-carousel/owl.carousel.min.js');

        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner($setting['banner_id']);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'title' => $result['title'],
                    'link'  => $result['link'],
                    'description'      =>  html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
                    'image' => $this->model_tool_image->cropsize($result['image'], $setting['width'], $setting['height'])
                );
            }
        }

        $data['module'] = $module++;

        return $this->load->view('extension/module/slideshow', $data);
    }

}