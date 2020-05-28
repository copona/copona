<?php
class ControllerExtensionModuleSlideshow extends Controller {

    public function index($setting) {
        static $module = 0;

        $this->load->model('design/banner');
        $this->load->model('tool/image');

        $this->document->addStyle('themes/default/assets/vendor/swiper/css/swiper.min.css');
        // $this->document->addStyle('themes/default/assets/vendor/swiper/css/opencart.css');
        $this->document->addScript('themes/default/assets/vendor/swiper/js/swiper.min.js');


        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner($setting['banner_id']);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'title'       => $result['title'],
                    'alt'         => empty($result['title']) ? 'Slideshow image' : $result['title'],
                    'link'        => $result['link'],
                    'description' => (isset($result['description'])) ? html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8') : '',
                    'image'       => $this->model_tool_image->{$this->config->get('theme_default_extension_module_slideshow_resize')}(
                        $result['image'],
                        $setting['width'],
                        $setting['height'],
                        ""
                        ,false //watermark
                    ),
                );
            }
        }

        $data['module'] = $module++;


        $this->hook->getHook('extension/module/slideshow/after', $data);

        return $this->load->view('extension/module/slideshow', $data);
    }

}