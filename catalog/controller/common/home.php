<?php
class ControllerCommonHome extends Controller {

    public function index() {
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));



        if (isset($this->request->get['route'])) {
            $this->document->addLink($this->config->get('config_url'), 'canonical');
        }

        $this->addStoreJsonLd();

        $data = $this->language->load('common/home');
        $data['template_name'] = $this->config->get('theme_default_directory') ? $this->config->get('theme_default_directory') : $this->config->get('config_template');
        //Current
        $data['theme_directory'] = $this->config->get('theme_default_directory') ? $this->config->get('theme_default_directory') : $this->config->get('config_template');


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->hook->getHook('controller/home/after', $data);
        $this->response->setOutput($this->load->view('common/home', $data));
    }

    /**
     * schema.org WebSite (with sitelinks search box) and Organization for the home page.
     */
    private function addStoreJsonLd() {
        $home = html_entity_decode($this->url->link('common/home'), ENT_QUOTES, 'UTF-8');
        $name = html_entity_decode((string)$this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

        $search = html_entity_decode($this->url->link('product/search', 'search=__QUERY__'), ENT_QUOTES, 'UTF-8');

        $this->document->addJsonLd([
            '@type'           => 'WebSite',
            'name'            => $name,
            'url'             => $home,
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => str_replace('__QUERY__', '{search_term_string}', $search),
                'query-input' => 'required name=search_term_string',
            ],
        ]);

        $organization = [
            '@type' => 'Organization',
            'name'  => $name,
            'url'   => $home,
        ];

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $organization['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
        }

        if ($this->config->get('config_email')) {
            $organization['email'] = $this->config->get('config_email');
        }

        if ($this->config->get('config_telephone')) {
            $organization['telephone'] = $this->config->get('config_telephone');
        }

        $this->document->addJsonLd($organization);
    }

}