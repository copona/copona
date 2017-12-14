<?php

class ControllerCheckoutCheckout extends Controller
{
    private $error = array();
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
        'serial',
        'vat_num',
        'bank_name',
        'bank_code',
        'bank_account',
        'serial' //- IS HARDCODED as an array
    );

    public function __construct($parms)
    {
        parent::__construct($parms);
        $this->load->model('localisation/country');
        $this->load->model('localisation/location');
    }

    public function index()
    {
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

        $data = $this->load->language('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('assets/vendor/datetimepicker/moment.js');
        $this->document->addScript('assets/vendor/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('assets/vendor/datetimepicker/bootstrap-datetimepicker.min.css');

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
            'href' => $this->url->link('checkout/checkout/guest', '', true)
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

    public function guest()
    {

        // Empty Cart Redirect
        if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
            //Empty cart redirect
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $data = $this->load->language('checkout/checkout');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/address');
        $this->load->model('account/customer');
        $this->session->data['checkout'] = isset($this->session->data['checkout']) ? $this->session->data['checkout'] : [];

        // Success Notification in session
        $data['success'] = '';
        $data['attention'] = '';
        $data['error_warning'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }
        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $data['error_warning']['error_stock'] = $this->language->get('error_stock');
        }


        //Set data default values
        if (empty($this->session->data['checkout'])) {
            $this->session->data['checkout']['customer_group_id'] = $this->config->get('config_customer_group_id');
            $this->session->data['checkout']['country_id'] = $this->config->get('config_country_id');
            $this->session->data['checkout']['zone_id'] = $this->config->get('config_zone_id');
            $this->session->data['checkout']['shipping_address'] = $this->model_localisation_location->getStoreAddress();
            $this->session->data['checkout']['order_shipping'] = 0;

            foreach ($this->allowed_post_values as $val) {
                $this->session->data['checkout'][$val] = empty($this->session->data['checkout'][$val]) ? '' : $this->session->data['checkout'][$val];
            }


            // foreach ($this->allowed_post_values as $val) {
            // $this->session->data['checkout'][$val] = isset($this->session->data['checkout'][$val]) ? $this->session->data['checkout'][$val] : '';
            // }

            if ($this->customer->isLogged()) { pr('logged in');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $customer_address = $this->model_account_address->getAddress($customer_info['address_id']);
                $customer_full_info = array_merge($customer_address, $customer_info);
                $customer_full_info += array('marketing_id' => '');

                foreach ($this->allowed_post_values as $val) {
                    if (array_key_exists($val, $customer_full_info)) {
                        $this->session->data['checkout'][$val] = $customer_full_info[$val];
                    }
                }

            }

        }

        // Fill with Post values.
        if ($this->request->post) {
            foreach ($this->allowed_post_values as $val) {
                $this->session->data['checkout'][$val] = (is_array($this->request->post($val)) ? preg_replace("/<.+>/sU", "",
                    $this->request->post($val)) : strip_tags($this->request->post($val)));
            }

            $payment_method = explode('.', $this->request->post('payment_method'));
            if($payment_method) {
                if (isset($this->session->data['payment_methods'][$method[0]]['template'])) {
                    $this->session->data['checkout']['payment_method'] = $this->session->data['payment_methods'][$payment_method[0]];
                    $this->session->data['checkout']['payment_method']['code'] .= '.' . $payment_method[1];
                } else {
                    $this->session->data['checkout']['payment_method'] = $this->session->data['payment_methods'][$payment_method[0]];
                }
            }


        }

        pr( $this->session->data['checkout']['payment_method']);

        // prd($this->session->data['checkout']);



        /* if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
            $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'),
              $this->url->link('account/register'));
        } else {
            $data['attention'] = '';
        } */



        // Load policy content
        $this->load->model('catalog/information');
        $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
        empty($information_info) ? $information_info = ['title' => 'Please, specify Terms in Admin!'] : false;
        $data['text_agree'] = sprintf($this->language->get('text_agree'),
            $this->url->link('information/information', 'information_id=' . $this->config->get('config_checkout_id'),
                'SSL'), $information_info['title'], $information_info['title']);


        $data['cart_total_value'] = round($this->cart->getTotal(), 2); //WIP: precision must be configurable
        $data['serial'] = !empty($this->session->data['checkout']['serial']) ? $this->session->data['checkout']['serial'] : [];

        $data['order_shipping'] = round($this->tax->calculate($this->session->data['shipping_method']['cost'],
            $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax')), 2);

        $data['cart_with_shipping'] = $data['cart_total_value'] + $data['order_shipping'];




        if ($this->request->post && $this->validateShipping() && $this->validate('checkout')) {
            /*
             * We will use ONE address at the moment.
             * This should be improved for Shipping/Payment different addresses.
             * Also, posibility for customer to print invoice from Profile with correct data.
             * */
            //PAYMENT DATA
            $data = $this->session->data['checkout']['payment'];

            $data['company_id'] = '';
            $data['tax_id'] = '';

            $data['firstname'] = $this->session->data['checkout']['firstname'];
            $data['lastname'] = $this->session->data['checkout']['lastname'];
            $data['company'] = $this->session->data['checkout']['company'];


            $data['address_1'] = $this->session->data['checkout']['address_1'];
            $data['city'] = $this->session->data['checkout']['city'];
            $data['postcode'] = $this->session->data['checkout']['postcode'];
            $data['zone_id'] = $this->session->data['checkout']['shipping_address']['zone_id'];
            $data['country'] = $this->session->data['checkout']['country'];
            $data['country_id'] = $this->session->data['checkout']['shipping_address']['country_id'];
            $data['address_format'] = '';
            $data['customer_group_id'] = $this->session->data['checkout']['customer_group_id'];
            $data['company_name'] = $this->session->data['checkout']['company_name'];
            $data['reg_num'] = $this->session->data['checkout']['reg_num'];
            $data['vat_num'] = $this->session->data['checkout']['vat_num'];
            $data['bank_name'] = $this->session->data['checkout']['bank_name'];
            $data['bank_code'] = $this->session->data['checkout']['bank_code'];
            $data['bank_account'] = $this->session->data['checkout']['bank_account'];
            $data['address_2'] = $this->session->data['checkout']['address_2'];

            $this->session->data['checkout']['payment'] = $data;

            $data = $this->session->data['checkout']['shipping_address'];

            $data['firstname'] = $this->session->data['checkout']['firstname'];

            $data['lastname'] = $this->session->data['checkout']['lastname'];
            $data['company'] = $this->session->data['checkout']['company'];

            $data['address_1'] = $this->session->data['checkout']['address_1'];

            $data['city'] = $this->session->data['checkout']['city'];
            $data['postcode'] = $this->session->data['checkout']['postcode'];
            $data['zone_id'] = $this->session->data['checkout']['shipping_address']['zone_id'];
            $data['country'] = $this->session->data['checkout']['country'];
            $data['country_id'] = $this->session->data['country_id'];
            $data['zone_id'] = $this->session->data['zone_id'];
            $data['address_format'] = '';

            $this->session->data['checkout']['shipping_address'] = $data;
            $this->session->data['checkout']['fax'] = '';
            //prd($this->session->data['checkout']['serial']);

            $this->response->redirect($this->url->link('checkout/confirm'));
        }

        // TODO: Remove array_merge !!!! please !!! :)
        // old templates depends on this, but should be removed.
        $data = array_merge ( $data, $this->session->data['checkout'] );
        // unset($this->session->data['checkout']);

        // prd($data);

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['action'] = $this->url->link('checkout/checkout/guest');
        $data['theme_default_directory'] = $this->config->get('theme_default_directory');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

        // This should also go to AJAX!
        $data['payment_method'] = $this->load->controller('checkout/payment_method');

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

        if ($type != 'checkout') {
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

        if (!empty($this->request->post['zone_id'])) {
            $this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
        }
        if (!empty($this->request->post['country_id'])) {
            $this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
        }
        if ($this->request->post['payment_method'] == "bank_transfer") {
            if ($this->request->post['customer_group_id'] == "2") {
                $serial_fields = Config::get('checkout_serial_fields');
                foreach ($serial_fields as $field) {
                    if (empty($this->request->post[$field]) || (utf8_strlen($this->request->post[$field]) < 1)) {
                        $this->error[$field] = $this->language->get('error_' . $field);
                    }
                }
            }
        }

        if (!isset($this->request->post['agree'])) {
            $this->session->data['agree'] = false;
            $this->session->data['checkout']['agree'] = false;
            $this->error['warning'] = sprintf($this->language->get('error_agree'),
                $this->url->link('information/information',
                    'information_id=' . $this->config->get('config_checkout_id'),
                    'SSL'), $this->config->get('config_name'));
        }


        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            if (!$product['stock']) {
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