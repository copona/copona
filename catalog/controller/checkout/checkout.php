<?php
class ControllerCheckoutCheckout extends Controller {
    private $error = array();
    private $is_redirect = false;
    private $allowed_post_values = array(
        'firstname',
        'lastname',
        'email',
        'telephone',
        'company',
        'country',
        'tax_id',
        'company_id',
        'city',
        'address_1',
        'address_2',
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
    );

    public function index() {
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $this->response->redirect($this->url->link('checkout/checkout/guest'));

        // Validate minimum quantity requirements.
        $products = $this->cart->cartProducts;

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->response->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->load->language('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        // Required by klarna
        if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
            $this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_checkout_option'] = sprintf($this->language->get('text_checkout_option'), 1);
        $data['text_checkout_account'] = sprintf($this->language->get('text_checkout_account'), 2);
        $data['text_checkout_payment_address'] = sprintf($this->language->get('text_checkout_payment_address'), 2);
        $data['text_checkout_shipping_address'] = sprintf($this->language->get('text_checkout_shipping_address'), 3);
        $data['text_checkout_shipping_method'] = sprintf($this->language->get('text_checkout_shipping_method'), 4);

        if ($this->cart->hasShipping()) {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
            $data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 6);
        } else {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
            $data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 4);
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['logged'] = $this->customer->isLogged();

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        $data['shipping_required'] = $this->cart->hasShipping();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('checkout/checkout', $data));
    }

    function guest() {

        $data = $this->load->language('checkout/checkout');
        $this->document->setTitle($this->language->get('heading_title'));

        //Set data default values
        if (isset($this->session->data['guest']['customer_group_id'])) {
            $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        } else {
            $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
            $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        }

        if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
            $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
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
            $customer_full_info += array( 'marketing_id' => '' ); //G TODO
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
            $data[$val] = $this->session->data['guest'][$val];
        }

        if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
            //Empty cart redirect
            $this->response->redirect($this->url->link('order/order/emptycart'));
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
        empty($information_info) ? $information_info = ['title' => 'Please, specify Terms in Admin!' ] : false;
        $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);

        // SHIPPING
        if (isset($this->request->post['shipping_method']) && $this->validateShipping()) {

            $shipping = explode('.', $this->request->post['shipping_method']);
            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
        }




        if ($this->request->post && $this->validate('guest')) {

            //PAYMENT DATA
            $data = $this->session->data['guest']['payment'];
            $data['firstname'] = $this->session->data['guest']['firstname'];
            $data['lastname'] = $this->session->data['guest']['lastname'];
            $data['company'] = $this->session->data['guest']['company'];
            $data['company_id'] = '';
            $data['tax_id'] = '';
            $data['address_1'] = $this->session->data['guest']['address_1'];
            $data['city'] = $this->session->data['guest']['city'];
            $data['postcode'] = '';
            $data['zone'] = '';
            $data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
            $data['country'] = $this->session->data['guest']['country'];
            $data['country_id'] = $this->session->data['guest']['shipping']['country_id'];
            $data['address_format'] = '';
            $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
            $data['company_name'] = $this->session->data['guest']['company_name'];
            $data['reg_num'] = $this->session->data['guest']['reg_num'];
            $data['vat_num'] = $this->session->data['guest']['vat_num'];
            $data['bank_name'] = $this->session->data['guest']['bank_name'];
            $data['bank_code'] = $this->session->data['guest']['bank_code'];
            $data['bank_account'] = $this->session->data['guest']['bank_account'];
            $data['address_2'] = $this->session->data['guest']['address_2'];

            $this->session->data['guest']['payment'] = $data;

            $data = $this->session->data['guest']['shipping'];

            $data['firstname'] = $this->session->data['guest']['firstname'];
            $data['lastname'] = $this->session->data['guest']['lastname'];
            $data['company'] = $this->session->data['guest']['company'];
            $data['company_id'] = '';
            $data['tax_id'] = '';
            $data['address_1'] = $this->session->data['guest']['address_1'];

            $data['city'] = $this->session->data['guest']['city'];
            $data['postcode'] = '';
            $data['zone'] = '';
            $data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
            $data['country'] = $this->session->data['guest']['country'];
            $data['country_id'] = $this->session->data['guest']['shipping']['country_id'];
            $data['address_format'] = '';
            $this->session->data['guest']['shipping'] = $data;
            $this->session->data['guest']['fax'] = '';

            $this->response->redirect($this->url->link('checkout/confirm'));
        } elseif ($this->request->post) {
            $this->session->data['error'] = $this->error;
            $this->response->redirect($this->url->link('checkout/checkout/guest', '', 'SSL'));
        } else {
            if (!isset($this->session->data['guest']['shipping'])) {
                $shipping_address['country_id'] = $this->config->get('config_country_id');
                $shipping_address['zone_id'] = $this->config->get('config_zone_id');
                $this->session->data['guest']['shipping'] = $shipping_address;
            }

            if (isset($this->session->data['error'])) {
                $data['error'] = $this->session->data['error'];
                unset($this->session->data['error']);
            }

            $data['cart_total_value'] = round($this->cart->getTotal(), 2);

            if (empty($this->session->data['shipping_method'])) {
                $data['order_shipping'] = 0;
            } else {
                $data['order_shipping'] = round($this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax')), 2);
            }
            $data['cart_with_shipping'] = $data['cart_total_value'] + $data['order_shipping'];
        }

        $data['shipping'] = 'empty_string_remove_in_controller';
        $this->load->model('localisation/country');
        $data['country_id'] = $this->session->data['guest']['shipping']['country_id'];
        $data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
        $data['countries'] = $this->model_localisation_country->getCountries();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['payment_method'] = $this->load->controller('checkout/payment_method');
        $data['cart'] = $this->load->controller('checkout/cart');

        $data['session'] = $this->session->data;

        $this->response->setOutput($this->load->view('checkout/guest', $data));
    }

    protected function validate($type = '') {
        $data = $this->language->load('checkout/guest');

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            if (!is_numeric($this->request->post['email']))
                $this->error['email'] = $this->language->get('error_email');
        }

        if ($type != 'guest') {
            if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                $this->error['warning'] = $this->language->get('error_exists'); }

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

        if (substr($this->request->post['shipping_method'], 0, 13) == 'pickup.pickup' && strlen($this->request->post['shipping_method']) < 10) {
            $this->error['shipping_method'] = $this->language->get('error_shipping_method_choose_shop');
            $this->session->data['error_shipping_pickup.pickup'] = true;
        } else { $this->session->data['error_shipping_pickup.pickup'] = false; }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ($this->request->post['payment_method'] == "bank_transfer") {
            if ($this->request->post['customer_group_id'] == "2") {
                if ((utf8_strlen($this->request->post['company_name']) < 3) || (utf8_strlen($this->request->post['company_name']) > 64)) {
                    $this->error['company_name'] = $this->language->get('error_company_name');
                }

                if ((utf8_strlen($this->request->post['reg_num']) < 3) || (utf8_strlen($this->request->post['reg_num']) > 64)) {
                    $this->error['reg_num'] = $this->language->get('error_reg_num');
                }
                if ((utf8_strlen($this->request->post['bank_name']) < 3) || (utf8_strlen($this->request->post['bank_name']) > 64)) {
                    $this->error['bank_name'] = $this->language->get('error_bank_name');
                }
                if ((utf8_strlen($this->request->post['bank_code']) < 3) || (utf8_strlen($this->request->post['bank_code']) > 64)) {
                    $this->error['bank_code'] = $this->language->get('error_bank_code');
                }
                if ((utf8_strlen($this->request->post['bank_account']) < 3) || (utf8_strlen($this->request->post['bank_account']) > 64)) {
                    $this->error['bank_account'] = $this->language->get('error_bank_account');
                }

                if ((utf8_strlen($this->request->post['address_2']) < 3) || (utf8_strlen($this->request->post['address_2']) > 64)) {
                    $this->error['address_2'] = $this->language->get('error_address_2');
                }
            }
        }

        if (!isset($this->request->post['agree'])) {
            $this->session->data['agree'] = false;
            $this->session->data['guest']['agree'] = false;
            //$this->error['warning'] = $this->language->get('error_agree');
            $this->error['warning'] = sprintf($this->language->get('error_agree'), $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $this->config->get('config_name'));
        }

        $shipping_method1 = $this->load->controller('checkout/shipping_method/validate');
        // Save posted values in session
        foreach ($this->request->post as $key => $val) {
            if (in_array($key, $this->allowed_post_values)) {
                $this->session->data['guest'][$key] = strip_tags($val);
                $this->session->data[$key] = strip_tags($val);
            }
        }

        if (isset($this->request->post['payment_method'])) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
        }

        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            if (!$product['stock']) {
                $this->error['not_ins_stock'] = $this->language->get('error_stock');
                pr($this->sessioni->data);
                break;
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateShipping() {
        if (!empty($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);
            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->error['warning'] = $this->language->get('error_shipping');
            }
        } else {
            $this->error['warning'] = $this->language->get('error_shipping');
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function country() {
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

    public function customfield() {
        $json = array();

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
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