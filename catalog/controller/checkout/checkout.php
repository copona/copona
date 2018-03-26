<?php

class ControllerCheckoutCheckout extends Controller
{
    public function __construct($parms)
    {
        parent::__construct($parms);
        $this->load->model('localisation/country');
        $this->load->model('localisation/location');
        $this->load->model('localisation/zone');
    }

    private $error = array();
    private $is_redirect = false;
    private $allowed_post_values = array(
      'firstname',
      'lastname',
      'email',
      'telephone',
      'company',
      'country',
      'zone_id',
      'country_id',
      'tax_id',
      'company_id',
      'city',
      'address_1',
      'address_2',
      'postcode',
      'comment',
      'agree',
      'marketing_id',
      'customer_group_id',
      'company_name',
      'reg_num',
      'vat_num',
      'bank_name',
      'bank_code',
      'bank_account',
        // 'serial' //- IS HARDCODED as an array
    );

    public function index()
    {
        $this->hook->getHook('checkout/checkout/index/before');
        $this->response->redirect($this->url->link('checkout/checkout/guest', '', 'SSL'));
    }

    public function guest()
    {
        $this->hook->getHook('checkout/checkout/guest/before');
        $data = $this->load->language('checkout/checkout');
        $this->document->setTitle($this->language->get('heading_title'));

        // Set data default values start
        foreach ($this->allowed_post_values as $val) {
            if (empty($this->session->data['guest'][$val])) {
                $this->session->data['guest'][$val] = '';
            }
        }

        if (empty($this->session->data['shipping_address'])) {
            $this->session->data['shipping_address'] = $this->model_localisation_location->getStoreAddress();
        }

        if (empty($this->session->data['guest']['customer_group_id'])) {
            $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
            $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'),
              $this->url->link('account/register'));
        } else {
            $data['attention'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }
        // Set data default values end

        // Set POST default values start
        // Save posted values in session
        foreach ($this->request->post as $key => $val) {
            if (in_array($key, $this->allowed_post_values)) {
                $this->session->data['guest'][$key] = (is_array($val) ? preg_replace("/<.+>/sU", "",
                  $val) : strip_tags($val));
                $this->session->data[$key] = (is_array($val) ? preg_replace("/<.+>/sU", "", $val) : strip_tags($val));
            }
        }

        if (!empty($this->request->post['serial'])) {
            unset($this->session->data['guest']['serial']);
            foreach (Config::get('checkout_serial_fields') as $field) {
                $this->session->data['guest']['serial'][$field] = !empty($this->request->post['serial'][$field]) ? $this->request->post['serial'][$field] : '';
            }
        } else {
            $serial = !empty($this->session->data['guest']['serial']) ? $this->session->data['guest']['serial'] : [];
            unset($this->session->data['guest']['serial']);

            foreach (Config::get('checkout_serial_fields') as $field) {
                $this->session->data['guest']['serial'][$field] = !empty($serial[ $field ]) ? $serial[ $field ] : false ;
            }
        }

        // prd($this->session->data['guest']['serial']);

        //prd();
        if (!empty($this->request->post['payment_method'])) {
            $method = explode('.', $this->request->post['payment_method']);
            if (isset($this->session->data['payment_methods'][$method[0]]['template'])) {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$method[0]];
                $this->session->data['payment_method']['code'] .= '.' . $method[1];
            } else {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$method[0]];
            }
        } elseif (empty($this->session->data['payment_method'])) {
            $this->flash->error( sprintf($this->language->get('error_no_payment'), Config::get('config_email') ) );
        }

        foreach (
          [
            'country_id',
            'zone_id',
            'city',
            'address_1',
            'postcode',
          ] as $field
        ) {
            $this->session->data['guest']['shipping_address'][$field] = $this->request->post($field);
        }


        //Set POST default values end


        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $data['error_warning']['error_stock'] = $this->language->get('error_stock');
        }
        // Check if customer is logged in and load his data
        $this->load->model('account/address');
        $this->load->model('account/customer');
        if ($this->customer->isLogged()) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $customer_address = $this->model_account_address->getAddress($customer_info['address_id']);
            $customer_full_info = array_merge($customer_address, $customer_info);
            $customer_full_info += array('marketing_id' => ''); //G TODO
            $customer_full_info['comment'] = '';
            $customer_full_info['agree'] = '';
        }
        // Fill fields with logged customer data
        if ($this->customer->isLogged() && !isset($this->session->data['logged_in'])) {
            foreach ($this->allowed_post_values as $val) {
                if (array_key_exists($val, $customer_full_info)) {
                    $this->session->data['guest'][$val] = $customer_full_info[$val];
                    $data[$val] = $this->session->data['guest'][$val];
                }
            }
            $this->session->data['logged_in'] = true;
        }

        //Fill fields for guest
        foreach ($this->allowed_post_values as $val) {
            if (!isset($this->session->data['guest'][$val])) {
                $this->session->data['guest'][$val] = '';
            }
        }

        if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
            //Empty cart redirect
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $data['logged'] = $this->customer->isLogged();
        $data['guest'] = 'index.php?route=checkout/checkout/guest';
        $data['action'] = $this->url->link('checkout/checkout/guest', '', 'SSL');
        $data['currency'] = $this->session->data['currency']; //vajag priekš visiem. Paysera jo īpaši.

        $data['text_recurring_item'] = $this->language->get('text_recurring_item');
        $data['text_next'] = $this->language->get('text_next');
        $data['text_next_choice'] = $this->language->get('text_next_choice');


        // Load policy content
        $this->load->model('catalog/information');
        $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
        empty($information_info) ? $information_info = ['title' => 'Please, specify Terms in Admin!'] : false;
        $data['text_agree'] = sprintf($this->language->get('text_agree'),
          $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'),
            'SSL'), $information_info['title'], $information_info['title']);

        if ($this->request->post && $this->validateShipping() && $this->validate('guest')) {

            // TODO: !!!! ??? 'payment' ??
            // $data = $this->session->data['guest']['payment'];


            $shipping_address = [];

            $shipping_address['company_id'] = '';
            $shipping_address['tax_id'] = '';
            $shipping_address['address_format'] = '';
            $shipping_address['firstname'] = $this->session->data['guest']['firstname'];
            $shipping_address['lastname'] = $this->session->data['guest']['lastname'];
            $shipping_address['company'] = $this->session->data['guest']['company'];
            $shipping_address['address_1'] = $this->session->data['guest']['address_1'];
            $shipping_address['city'] = $this->session->data['guest']['city'];
            $shipping_address['postcode'] = $this->session->data['guest']['postcode'];
            $shipping_address['zone_id'] = $this->session->data['guest']['shipping_address']['zone_id'];
            $shipping_address['zone'] = $this->model_localisation_zone->getZone($shipping_address['zone_id'])['name'];
            $shipping_address['country_id'] = $this->session->data['guest']['shipping_address']['country_id'];
            $shipping_address['country'] = $this->model_localisation_country->getCountry($shipping_address['country_id'])['name'];
            $shipping_address['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
            $shipping_address['company_name'] = $this->session->data['guest']['company_name'];
            $shipping_address['reg_num'] = $this->session->data['guest']['reg_num'];
            $shipping_address['vat_num'] = $this->session->data['guest']['vat_num'];
            $shipping_address['bank_name'] = $this->session->data['guest']['bank_name'];
            $shipping_address['bank_code'] = $this->session->data['guest']['bank_code'];
            $shipping_address['bank_account'] = $this->session->data['guest']['bank_account'];
            $shipping_address['address_2'] = $this->session->data['guest']['address_2'];

            $this->session->data['guest']['payment_address'] = $shipping_address;
            $this->session->data['guest']['shipping_address'] = $shipping_address;
            $this->session->data['guest']['fax'] = '';
            // prd($shipping_address);
            $this->response->redirect($this->url->link('checkout/confirm'));
        } elseif ($this->request->post) {
            $this->session->data['error'] = $this->error;

            $this->response->redirect($this->url->link('checkout/checkout/guest', '', 'SSL'));
        } else {

            if (isset($this->session->data['error'])) {
                $data['error'] = $this->session->data['error'];
                unset($this->session->data['error']);
            }

            $data['cart_total_value'] = round($this->cart->getTotal(), 2);
            $data['serial'] = !empty($this->session->data['guest']['serial']) ? $this->session->data['guest']['serial'] : [];

            $data['order_shipping'] = 0;
            if (!empty($this->session->data['shipping_method'])) {
                $data['order_shipping'] = round($this->tax->calculate($this->session->data['shipping_method']['cost'],
                  $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax')), 2);
            }
            $data['cart_with_shipping'] = $data['cart_total_value'] + $data['order_shipping'];
        }

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['payment_method'] = $this->load->controller('checkout/payment_method');

        $data = array_merge($data, $this->session->data['guest']);

        $this->hook->getHook('checkout/guest/after', $data);
        $this->response->setOutput($this->load->view('checkout/guest', $data));

    }

    protected function validate($type = '')
    {
        $this->language->load('checkout/guest');

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i',
            $this->request->post['email'])) {
            if (!is_numeric($this->request->post['email'])) {
                $this->error['email'] = $this->language->get('error_email');
            }
        }

        if ($type != 'guest') {
            if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }

            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 30)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['confirm'] != $this->request->post['password']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 300)) {
            if ($this->request->post['validate_address']) {
                $this->error['address_1'] = $this->language->get('error_address_1');
            }
        }

        if (!empty($this->request->post['shipping_method']) && substr($this->request->post['shipping_method'], 0,
            13) == 'pickup.pickup' && strlen($this->request->post['shipping_method']) < 10) {
            $this->error['shipping_method'] = $this->language->get('error_shipping_method_choose_shop');
            $this->session->data['error_shipping_pickup.pickup'] = true;
        } else {
            $this->session->data['error_shipping_pickup.pickup'] = false;
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ($this->request->post['payment_method'] == "bank_transfer") {
            if ($this->request->post['customer_group_id'] == "2") {


                foreach (Config::get('checkout_serial_fields') as $field) {
                    if (empty($this->request->post['serial'][$field]) || (utf8_strlen($this->request->post['serial'][$field]) < 1)) {
                        $this->error[$field] = $this->language->get('error_' . $field);
                    }
                }
            }
        }


        if (!isset($this->request->post['agree'])) {
            $this->session->data['agree'] = false;
            $this->session->data['guest']['agree'] = false;
            //$this->error['warning'] = $this->language->get('error_agree');
            $this->error['warning'] = sprintf($this->language->get('error_agree'),
              $this->url->link('information/information',
                'information_id=' . $this->config->get('config_checkout_id'),
                'SSL'), $this->config->get('config_name'));
        }


        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            if (!$product['stock'] && !$this->config->get('config_stock_checkout')) {
                $this->error['not_ins_stock'] = $this->language->get('error_stock');
                break;
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateShipping()
    {
        if (!empty($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);
            if (!isset($shipping[0]) || !isset($shipping[1]) ||
                !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->error['warning'] = $this->language->get('error_shipping');
            }
        } else {
            $this->error['warning'] = $this->language->get('error_shipping');
        }

        //pr($this->error);

        if (!$this->error) {

            $shipping = explode('.', $this->request->post['shipping_method']);
            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

            return true;
        } else {
            return false;
        }
    }

    public function country()
    {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
              'country_id'        => $country_info['country_id'],
              'name'              => $country_info['name'],
              'iso_code_2'        => $country_info['iso_code_2'],
              'iso_code_3'        => $country_info['iso_code_3'],
              'address_format'    => $country_info['address_format'],
              'postcode_required' => $country_info['postcode_required'],
              'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
              'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function customfield()
    {
        $json = array();

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'],
            $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = array(
              'custom_field_id' => $custom_field['custom_field_id'],
              'required'        => $custom_field['required']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}