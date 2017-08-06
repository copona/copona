<?php
class ControllerSettingSetting extends Controller {
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('localisation/currency');
        $this->load->model('setting/setting');
        $this->load->model('localisation/currency');
        $this->load->model('extension/extension');
        $this->load->model('design/layout');
        $this->load->model('tool/image');
        $this->load->model('localisation/location');
        $this->load->model('localisation/country');
        $this->load->model('localisation/language');
        $this->load->model('localisation/currency');
        $this->load->model('localisation/length_class');
        $this->load->model('localisation/weight_class');
        $this->load->model('customer/customer_group');
        $this->load->model('catalog/information');
        $this->load->model('localisation/order_status');
        $this->load->model('user/api');
        $this->load->model('localisation/return_status');
        $this->load->model('extension/extension');
    }

    public function index() {
        $data = $this->load->language('catalog/product');
        $data = array_merge($data, $this->load->language('setting/setting'));

        $this->document->setTitle($this->language->get('heading_title'));



        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('config', $this->request->post);

            if ($this->config->get('config_currency_auto')) {
                $this->model_localisation_currency->refresh();
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['save_continue']) && $this->request->post['save_continue'])
                $this->response->redirect($this->url->link('setting/setting', '&token=' . $this->session->data['token'], true));
            else
                $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], true));
        }

        $errors = array(
            'warning',
            'name',
            'owner',
            'address',
            'email',
            'meta_title',
            'country',
            'zone',
            'customer_group_display',
            'login_attempts',
            'voucher_min',
            'voucher_max',
            'processing_status',
            'complete_status',
            'ftp_hostname',
            'ftp_port',
            'ftp_username',
            'ftp_password',
            'error_filename',
            'limit_admin',
            'encryption',
        );

        foreach ($errors as $val) {
            if (isset($this->error[$val])) {
                $data['error_' . $val] = $this->error[$val];
            } else {
                $data['error_' . $val] = '';
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_stores'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);

        $data['token'] = $this->session->data['token'];

        $config_values = $this->model_setting_setting->getSetting('config');
        foreach($config_values as $key => $value) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }

        $data['store_url'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG;

        $data['themes'] = array();



        $extensions = $this->model_extension_extension->getInstalled('theme');

        foreach ($extensions as $code) {
            $this->load->language('extension/theme/' . $code);

            $data['themes'][] = array(
                'text'  => $this->language->get('heading_title'),
                'value' => $code
            );
        }


        $data['layouts'] = $this->model_design_layout->getLayouts();

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


        $data['locations'] = $this->model_localisation_location->getLocations();

        if (isset($this->request->post['config_location'])) {
            $data['config_location'] = $this->request->post['config_location'];
        } elseif ($this->config->get('config_location')) {
            $data['config_location'] = $this->config->get('config_location');
        } else {
            $data['config_location'] = array();
        }

        $data['countries'] = $this->model_localisation_country->getCountries();


        $data['languages'] = $this->model_localisation_language->getLanguages(array(
            'all' ));
        //prd($data['languages']);

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        // part numbers adminisitration

        $data['partnumbers'] = ['sku', 'upc', 'ean', 'jan', 'isbn', 'mpn' ];
        foreach ($data['partnumbers'] as $partnumber) {
            if (isset($this->request->post['config_use_' . $partnumber])) {
                $data['config_use_' . $partnumber] = $this->request->post['config_use_' . $partnumber];
            } else {
                $data['config_use_' . $partnumber] = $this->config->get('config_use_' . $partnumber);
            }
        }

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        if (isset($this->request->post['config_customer_group_display'])) {
            $data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
        } elseif ($this->config->get('config_customer_group_display')) {
            $data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
        } else {
            $data['config_customer_group_display'] = array();
        }


        if (isset($this->request->post['config_login_attempts'])) {
            $data['config_login_attempts'] = $this->request->post['config_login_attempts'];
        } elseif ($this->config->has('config_login_attempts')) {
            $data['config_login_attempts'] = $this->config->get('config_login_attempts');
        } else {
            $data['config_login_attempts'] = 5;
        }



        $data['informations'] = $this->model_catalog_information->getInformations();

        if (isset($this->request->post['config_invoice_prefix'])) {
            $data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
        } elseif ($this->config->get('config_invoice_prefix')) {
            $data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
        } else {
            $data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
        }

        if (isset($this->request->post['config_processing_status'])) {
            $data['config_processing_status'] = $this->request->post['config_processing_status'];
        } elseif ($this->config->get('config_processing_status')) {
            $data['config_processing_status'] = $this->config->get('config_processing_status');
        } else {
            $data['config_processing_status'] = array();
        }

        if (isset($this->request->post['config_complete_status'])) {
            $data['config_complete_status'] = $this->request->post['config_complete_status'];
        } elseif ($this->config->get('config_complete_status')) {
            $data['config_complete_status'] = $this->config->get('config_complete_status');
        } else {
            $data['config_complete_status'] = array();
        }


        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


        $data['apis'] = $this->model_user_api->getApis();

        if (isset($this->request->post['config_affiliate_approval'])) {
            $data['config_affiliate_approval'] = $this->request->post['config_affiliate_approval'];
        } elseif ($this->config->has('config_affiliate_approval')) {
            $data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
        } else {
            $data['config_affiliate_approval'] = '';
        }

        if (isset($this->request->post['config_affiliate_auto'])) {
            $data['config_affiliate_auto'] = $this->request->post['config_affiliate_auto'];
        } elseif ($this->config->has('config_affiliate_auto')) {
            $data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
        } else {
            $data['config_affiliate_auto'] = '';
        }

        if (isset($this->request->post['config_affiliate_commission'])) {
            $data['config_affiliate_commission'] = $this->request->post['config_affiliate_commission'];
        } elseif ($this->config->has('config_affiliate_commission')) {
            $data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
        } else {
            $data['config_affiliate_commission'] = '5.00';
        }

        $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

        if (isset($this->request->post['config_captcha'])) {
            $data['config_captcha'] = $this->request->post['config_captcha'];
        } else {
            $data['config_captcha'] = $this->config->get('config_captcha');
        }


        $data['captchas'] = array();

        // Get a list of installed captchas
        $extensions = $this->model_extension_extension->getInstalled('captcha');

        foreach ($extensions as $code) {
            $this->load->language('extension/captcha/' . $code);

            if ($this->config->get($code . '_status')) {
                $data['captchas'][] = array(
                    'text'  => $this->language->get('heading_title'),
                    'value' => $code
                );
            }
        }

        if (isset($this->request->post['config_captcha_page'])) {
            $data['config_captcha_page'] = $this->request->post['config_captcha_page'];
        } elseif ($this->config->has('config_captcha_page')) {
            $data['config_captcha_page'] = $this->config->get('config_captcha_page');
        } else {
            $data['config_captcha_page'] = array();
        }

        $data['captcha_pages'] = array();

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_register'),
            'value' => 'register'
        );

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_guest'),
            'value' => 'guest'
        );

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_review'),
            'value' => 'review'
        );

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_return'),
            'value' => 'return'
        );

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_contact'),
            'value' => 'contact'
        );

        if (isset($this->request->post['config_logo'])) {
            $data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $data['config_logo'] = $this->config->get('config_logo');
        }

        if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
            $data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
        } else {
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
            $data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_ftp_hostname'])) {
            $data['config_ftp_hostname'] = $this->request->post['config_ftp_hostname'];
        } elseif ($this->config->get('config_ftp_hostname')) {
            $data['config_ftp_hostname'] = $this->config->get('config_ftp_hostname');
        } else {
            $data['config_ftp_hostname'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
        }

        if (isset($this->request->post['config_ftp_port'])) {
            $data['config_ftp_port'] = $this->request->post['config_ftp_port'];
        } elseif ($this->config->get('config_ftp_port')) {
            $data['config_ftp_port'] = $this->config->get('config_ftp_port');
        } else {
            $data['config_ftp_port'] = 21;
        }

        if (isset($this->request->post['config_mail_smtp_port'])) {
            $data['config_mail_smtp_port'] = $this->request->post['config_mail_smtp_port'];
        } elseif ($this->config->has('config_mail_smtp_port')) {
            $data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
        } else {
            $data['config_mail_smtp_port'] = 25;
        }

        if (isset($this->request->post['config_mail_smtp_timeout'])) {
            $data['config_mail_smtp_timeout'] = $this->request->post['config_mail_smtp_timeout'];
        } elseif ($this->config->has('config_mail_smtp_timeout')) {
            $data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
        } else {
            $data['config_mail_smtp_timeout'] = 5;
        }

        if (isset($this->request->post['config_mail_alert'])) {
            $data['config_mail_alert'] = $this->request->post['config_mail_alert'];
        } elseif ($this->config->has('config_mail_alert')) {
            $data['config_mail_alert'] = $this->config->get('config_mail_alert');
        } else {
            $data['config_mail_alert'] = array();
        }

        $data['mail_alerts'] = array();

        $data['mail_alerts'][] = array(
            'text'  => $this->language->get('text_mail_account'),
            'value' => 'account'
        );

        $data['mail_alerts'][] = array(
            'text'  => $this->language->get('text_mail_affiliate'),
            'value' => 'affiliate'
        );

        $data['mail_alerts'][] = array(
            'text'  => $this->language->get('text_mail_order'),
            'value' => 'order'
        );

        $data['mail_alerts'][] = array(
            'text'  => $this->language->get('text_mail_review'),
            'value' => 'review'
        );

        if (isset($this->request->post['config_file_max_size'])) {
            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
        } elseif ($this->config->get('config_file_max_size')) {
            $data['config_file_max_size'] = $this->config->get('config_file_max_size');
        } else {
            $data['config_file_max_size'] = 300000;
        }

        // Default tax class
        $this->load->model('localisation/tax_class');
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
        if (isset($this->request->post['config_tax_class_id'])) {
            $data['config_tax_class_id'] = $this->request->post['config_tax_class_id'];
        } else {
            $data['config_tax_class_id'] = $this->config->get('config_tax_class_id');
        }
        // End default tax class

        if(isset($this->request->post['config_social_media'])) {
            $data['config_social_media'] = json_encode($this->request->post['config_social_media']);
        }
        else{
            $data['config_social_media'] = is_array($this->config->get('config_social_media')) ? $this->config->get('config_social_media') : array();
            foreach($data['config_social_media'] as &$social_media){
                $social_media['real_icon_path'] =  $this->model_tool_image->resize($social_media['icon'], 32, 32);;
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/setting', $data));
    }

    protected function validate() {

        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // Set validationi rules: field|minLength|maxLength
        $validate = array(
            'meta_title',
            'name',
            'owner|3|64',
            'address|3|256',
            //'telephone|3|32',
            'limit_admin',
            'login_attempts|1',
            'voucher_min|1',
            'voucher_max|1',
            'processing_status',
            'complete_status',
            'encryption|32|1024',
        );

        // Simple validation loop.
        foreach ($validate as $val) {
            $parts = explode('|', $val);
            !empty($parts[1]) ? $min = $parts[1] : $min = 0;
            !empty($parts[2]) ? $max = $parts[2] : $max = 0;

            $min = $min ? utf8_strlen($this->request->post['config_' . $parts[0]]) < $min : false;
            $max = $max ? utf8_strlen($this->request->post['config_' . $parts[0]]) > $max : false;

            if (!$this->request->post['config_' . $parts[0]] || $max || $min) {
                $this->error[$parts[0]] = $this->language->get('error_' . $parts[0]);
            }
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }

        if ($this->request->post['config_ftp_status']) {
            if (!$this->request->post['config_ftp_hostname']) {
                $this->error['ftp_hostname'] = $this->language->get('error_ftp_hostname');
            }

            if (!$this->request->post['config_ftp_port']) {
                $this->error['ftp_port'] = $this->language->get('error_ftp_port');
            }


            if (!$this->request->post['config_ftp_username']) {
                $this->error['ftp_username'] = $this->language->get('error_ftp_username');
            }

            if (!$this->request->post['config_ftp_password']) {
                $this->error['ftp_password'] = $this->language->get('error_ftp_password');
            }
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_filename'] = $this->language->get('error_error_filename');
        } else {
            if (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
                $this->error['error_filename'] = $this->language->get('error_malformed_filename');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }
        //pr($this->error);
        return !$this->error;
    }

    public function theme() {
        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        // This is only here for compatibility with old themes.
        if ($this->request->get['theme'] == 'theme_default') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = basename($this->request->get['theme']);
        }

        if (is_file(DIR_PUBLIC . '/themes/' . $theme . '/assets/img/' . $theme . '.png')) {
            $this->response->setOutput($server . 'themes/' . $theme . '/assets/img/' . $theme . '.png');
        } else {
            $this->response->setOutput($server . 'image/no_image.png');
        }
    }

}