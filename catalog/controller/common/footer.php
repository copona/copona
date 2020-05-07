<?php
class ControllerCommonFooter extends Controller {

    public function index() {
        $data = $this->load->language('common/footer');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;
        //Deprecated
        $data['template_name'] = $this->config->get('theme_default_directory') ? $this->config->get('theme_default_directory') : $this->config->get('config_template');
        //Current
        $data['theme_directory'] = $this->config->get('theme_default_directory') ? $this->config->get('theme_default_directory') : $this->config->get('config_template');

        $data['scripts'] = $this->document->getScripts('footer');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                );
            }
        }

        $data['contact'] = $this->url->link('information/contact');
        $data['return'] = $this->url->link('account/return/add', '', true);
        $data['sitemap'] = $this->url->link('information/sitemap');
        $data['manufacturer'] = $this->url->link('product/manufacturer');
        $data['voucher'] = $this->url->link('account/voucher', '', true);
        $data['affiliate'] = $this->url->link('affiliate/account', '', true);
        $data['special'] = $this->url->link('product/special');
        $data['account'] = $this->url->link('account/account', '', true);
        $data['order'] = $this->url->link('account/order', '', true);
        $data['wishlist'] = $this->url->link('account/wishlist', '', true);
        $data['newsletter'] = $this->url->link('account/newsletter', '', true);

        $information_info = $this->model_catalog_information->getInformation(Config::get('config_account_id'));

        //TODO: Terms rename, replace, add additional variable for admin options?
        $data['terms'] = $information_info ? $this->url->link('information/information', 'information_id=' . $information_info['information_id'])  : '' ;
        $data['terms_title'] = $information_info ? $information_info['title'] : '' ;

        // For EDIT link in footer.
        $data['token'] = $this->session->data('token');

        if ($data['token']) {
            $data['token'] = $this->session->data['token'];
            $data['route'] = $this->request->get('route');
            $data['product_id'] = $this->request->get('product_id');

            $parts = explode('_', (string)$this->request->get('path'));
            $data['category_id'] = (int)array_pop($parts);

            if ($data['category_id']) {
                $category = $this->model_catalog_category->getCategory($data['category_id']);
            }

            $data['path'] = $this->request->get('path');
            $data['information_id'] = $this->request->get('information_id');
            $data['infocategory_id'] = $this->request->get('infocategory_id');
            $data['manufacturer_id'] = $this->request->get('manufacturer_id');
            $data['filter_name'] = $this->request->get('search');
        }


        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

        // Whos Online
        if ($this->config->get('config_customer_online')) {
            $this->load->model('tool/online');

            if (isset($this->request->server['HTTP_X_REAL_IP'])) {
                $ip = $this->request->server['HTTP_X_REAL_IP'];
            } else if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                $url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            } else {
                $url = '';
            }

            if (isset($this->request->server['HTTP_REFERER'])) {
                $referer = $this->request->server['HTTP_REFERER'];
            } else {
                $referer = '';
            }

            $this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
        }
        $this->hook->getHook('footer/index/after', $data);
        return $this->load->view('common/footer', $data);
    }

}
