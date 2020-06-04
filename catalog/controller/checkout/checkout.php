<?php
// composer require ph-7/eu-vat-validator
use PH7\Eu\Vat\Validator;
use PH7\Eu\Vat\Provider\Europa;

class ControllerCheckoutCheckout extends Controller
{

    private $checkout_hide_tax_id;
    private $checkout_hide_company_id;
    private $error;

    public function __construct($registry)
    {
        parent::__Construct($registry);

        $this->load->language('checkout/checkout');
        $this->load->model('localisation/country');
        $this->load->model('account/customer');
        $this->load->model('localisation/country');


    }


    public function deleteCustomer($customer_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function index()
    {
        // https://gp.11.indeed.pro/e-veikals/?route=extension/payment/swedbank_portal/callback_failure&dts_reference=3700900030263517


        $data = $this->load->language('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        // $this->document->addScript('catalog/view/javascript/jquery/jquery.colorbox-min.js');
        // $this->document->addStyle('catalog/view/javascript/jquery/colorbox.css');
        // $this->document->addStyle('catalog/view/javascript/jquery/checkout.css');

        $data['checkout_hide_tax_id'] = false; //config
        $data['checkout_hide_company_id'] = false; //config

        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            // $this->response->redirect($this->url->link('checkout/cart'));
            // This, log, only for DEBUG time:
            $this->log->write( $this->language->get('text_warning_not_enough_in_stock') );
            $this->flash->error( $this->language->get('text_warning_not_enough_in_stock') );
        }


        if (isset($this->session->data['customer_id'])) {
            $data['customer_id'] = $this->session->data['customer_id'];
            if (isset($this->session->data['checkout_customer_id']) && $this->session->data['checkout_customer_id'] === true) {
                //cleanup previous incomplete checkout attempts

                $this->cart->unset();

                //if customer account was created by checkout module then delete it
                //$this->deleteCustomer($this->session->data['customer_id']);
                //unset($this->session->data['checkout_customer_id']);
            } else {
                //	$this->customer->logout();
            }
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }


        $data['shipping_methods'] = $this->cart->getShippingMethods();

        // $data['code'] = $this->cart->getShippingMethod()['code'];

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $data['payment_methods'] = [];
        }


        // Totals, Products
        $data['products'] = $this->cart->getProducts(); // Varbu't vajadzēs!
        $data['totals'] = $this->cart->getTotals_azon();

        $data['vouchers'] = [];

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $data['vouchers'][] = [
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount']),
                    'remove'      => $this->url->link('checkout/cart', 'remove=' . $key),
                ];
            }
        }


        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {

                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'),
                    $information_info['title'], $information_info['title']);

            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }


        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        $data['payment'] = '';
        $this->load->model('account/address');


        //var_dump($this->session->data);
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $this->load->model('account/custom_field');
        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

        $data['address'] = $this->cart->getShippingAddress();
        $data['address2'] = $this->cart->getAddress2();


        // $this->validate();

        $this->login(false, $data);
        $this->guest(false, $data);

        $this->checkout(false, $data);

        // $this->flash->display(); prd();

        $this->shipping_address(false, $data);

        // Set/Load initial/session shipping method!
        $this->shipping_method(false, $data);

        $this->payment_address(false, $data);
        $this->payment_method(false, $data);
        // $this->confirm(false, $data);

        if (isset($this->request->get['quickconfirm'])) {
            $data['quickconfirm'] = $this->request->get['quickconfirm'];
        }

        if ($this->customer->isLogged()) {
            $data = array_merge($data, $this->model_account_address->getAddress($this->customer->getAddressId()));
            $data['email'] = $this->customer->getEmail();
            $data['telephone'] = $this->customer->getTelephone();
            // $data['firstname'] = $this->customer->getFirstName();
            // $data['lastname'] = $this->customer->getLastName();
            $data['payment_address_id'] = $this->customer->getAddressId();
        }


        $this->response->setOutput($this->load->view('checkout/easy_checkout', $data));

    }


    public function shipping_address($render = true, &$data = [])
    {

        $this->load->language('checkout/checkout');

        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->session->data['shipping_address_id'])) {
            $data['shipping_address_id'] = $this->session->data['shipping_address_id'];
        } else {
            $data['shipping_address_id'] = $this->customer->getAddressId();
        }

        $this->load->model('account/address');

        $data['addresses'] = $this->model_account_address->getAddresses();

        if (isset($this->session->data['shipping_postcode'])) {
            $data['postcode'] = $this->session->data['shipping_postcode'];
        } else {
            $data['postcode'] = '';
        }

        if (isset($this->session->data['shipping_country_id'])) {
            $data['country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $data['country_id'] = 0; // $this->config->get('config_country_id');
        }

        if (isset($this->session->data['shipping_zone_id'])) {
            $data['zone_id'] = $this->session->data['shipping_zone_id'];
        } else {
            $data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if ($render !== false) {
            $this->response->setOutput($this->load->view('checkout/shipping_address', $data));
        }
    }

    public function shipping_method($render = true, &$data = [])
    {

        $this->load->language('checkout/checkout');

        $this->load->model('account/address');

        $data['text_checkout_shipping_method'] = sprintf($this->language->get('text_checkout_shipping_method'), 4);
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');


        if (!$this->cart->getShippingMethods()) {
            $data['error_no_shipping'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_no_shipping'] = '';
        }


        $data['shipping_methods'] = $this->cart->getShippingMethods();

        // TODO: jāpārtaisa, uz shipping_code lai visur ir! Code ir slikts namespace!

        $data['shipping_code'] = $this->cart->getShippingMethodCode();
        $data['code'] = $this->cart->getShippingMethodCode();

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }


        // prd();

        // prd($data);

        if ($render !== false) {
            $this->response->setOutput($this->load->view('checkout/easy_shipping_method', $data));
        }
    }


    public function payment_address($render = true, &$data = [])
    {

        $this->load->language('checkout/checkout');

        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_company'] = $this->language->get('entry_company');

        // Two new translations:
        $data['entry_company_id'] = $this->language->get('entry_company_id');
        $data['entry_tax_id'] = $this->language->get('entry_tax_id');

        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->session->data['payment_address']['address_id'])) {
            $data['payment_address_id'] = $this->session->data['payment_address']['address_id'];
        } else {
            $data['payment_address_id'] = $this->customer->getAddressId();
        }


        $data['addresses'] = [];

        $this->load->model('account/address');

        $data['addresses'] = $this->model_account_address->getAddresses();
        //$this->session->data['addresses'] = $data['addresses'];


        $this->load->model('account/customer_group');


        if (isset($this->session->data['payment_address']['country_id'])) {
            $data['country_id'] = $this->session->data['payment_address']['country_id'];
        } else {
            $data['country_id'] = 0; // $this->config->get('config_country_id');
        }

        if (isset($this->session->data['payment_address']['zone_id'])) {
            $data['zone_id'] = $this->session->data['payment_address']['zone_id'];
        } else {
            $data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields(['filter_customer_group_id' => $this->config->get('config_customer_group_id')]);

        if (isset($this->session->data['payment_address']['custom_field'])) {
            $data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
        } else {
            $data['payment_address_custom_field'] = [];
        }


        if ($render !== false) {

            $this->response->setOutput($this->load->view('checkout/payment_address', $data));


        }
    }


    public function payment_method($render = true, &$data = [])
    {

        $this->load->language('checkout/checkout');
        $this->load->model('account/address');

        $payment_address = $this->model_account_address->getAddress((isset($this->request->post['payment_address_id'])) ? $this->request->post['payment_address_id'] : 0);


        if (isset($this->request->post['country_id'])) {
            $this->session->data['guest']['payment']['country_id'] = $payment_address['country_id'] = $this->request->post['country_id'];

            $this->session->data['shipping_country_id'] =
            $this->session->data['payment_country_id'] = $this->session->data['guest']['payment']['payment_country_id'] = $payment_address['payment_country_id'] =
                $this->request->post['country_id'];

            //            // Todo: zone_id
            //            $this->session->data['shipping_zone_id']
            //                = $this->session->data['payment_zone_id']
            //                = $this->session->data['guest']['payment']['zone_id']
            //                = $payment_address['zone_id']
            //                = (empty($this->request->post['zone_id']) ? 0 : $this->request->post['zone_id']);

        } elseif ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest']['payment'])) {


            $payment_address = $this->session->data['guest']['payment'];
        }


        // $this->cart->setPaymentAddress( $payment_address );

        $this->cart->setPaymentMethods();
        $data['payment_methods'] = $this->cart->getPaymentMethods();


        if ($this->cart->hasShipping()) {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
        } else {
            $data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
        }
        //$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 4);
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_comments'] = $this->language->get('text_comments');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['payment_methods'])) {
            $data['error_no_payment'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $data['error_no_payment'] = '';
        }


        $fields = [
            'comment'      => [],
            'code_payment' => [],
            // 'payment_methods' => [], // metode .cart->
            'agree'        => [],
        ];

        //@INDEED - defining fields in save for payment
        foreach ($fields as $key => $field) {
            $data[$key] = isset($this->session->data[$key]) ? $this->session->data[$key] : '';
        }


        // Esmu izlasījis (-usi) un piekrītu preču saņemšanai un noteikumiem

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'),
                    $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $this->processPostData();
        $payment_method = $this->cart->getPaymentMethod();

        if ($payment_method && isset($payment_method['code'])) {
            $data['payment_method_code'] = $payment_method['code'];
        } else {
            $data['payment_method_code'] = '';
        }

        if ($render !== false) {
            $this->response->setOutput($this->load->view('checkout/easy_payment_method', $data));
        }
    }

    public function payment_method_set($render = true, &$data = [])
    {

        // šo vajag šeit, jo Adreses gadījumā - tas tiek postēts ar visiem valsts datiem,
        // bet piegādei - vajag jau sadefinētas adreses, lai šis strādātu.

        $this->cart->setPaymentMethods();


        $json = [];


        if (!$this->request->get('payment_method')) {
            $json['error']['warning'] = $this->language->get('error_payment');
        } else {
            $this->cart->setPaymentMethod($this->request->get('payment_method'));
        }

        $this->response->setOutput(json_encode($json));

    }

    public function checkout($render = true, &$data = [])
    {
        // Validate cart has products and has stock.
        // WHERE DO WE NEED THIS METHOD AT ALL ?
        // Validate minimum quantity requirments.
        // $products = $this->cart->getProducts();
        // foreach ($products as $product) {
        //     $product_total = 0;
        //     foreach ($products as $product_2) {
        //         if ($product_2['product_id'] == $product['product_id']) {
        //             $product_total += $product_2['quantity'];
        //         }
        //     }
        //
        //     if ($product['minimum'] > $product_total) {
        //
        //     }
        // }

        $this->load->language('checkout/checkout');
        $this->document->setTitle($this->language->get('heading_title'));
        // $this->document->addScript('assets/js/easy_checkout/jquery.colorbox-min.js');
        // $this->document->addStyle('assets/js/easy_checkout/colorbox.css');
        // $this->document->addStyle('assets/js/easy_checkout/checkout.css');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
            'separator' => $this->language->get('text_separator'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_modify'] = $this->language->get('text_modify');
        $data['logged'] = $this->customer->isLogged();
        $data['shipping_required'] = $this->cart->hasShipping();

        $data['cart_table'] = $this->cart(false);

        if ($render !== false) {
            if (isset($this->request->get['quickconfirm'])) {
                $data['quickconfirm'] = $this->request->get['quickconfirm'];
            }
        }
    }

    public function validate($data = [], $render = true)
    {

        $this->load->language('checkout/cart');
        $this->load->language('checkout/checkout');


        $json = [];


        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'],
                $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }


        if (!isset($json['error'])) {
            $json = array_merge($json, $this->payment_address_validate());
        }


        if (!isset($json['error'])) {
            $json = array_merge($json, $this->shipping_address_validate());
        }

        // Šis arī Setto adress2 !
        if (!isset($json['error'])) {
            $json = array_merge($json, $this->address2_validate());
        }


        if (!isset($json['error']) && !$this->customer->isLogged()) {
            $json = array_merge($json, $this->shipping_method_validate());
            if (isset($json['error'])) {
                $this->log->write($json['error']);
            }
        }


        if (!isset($json['error'])) {
            $json = array_merge($json, $this->payment_method_validate());
        }

        // Validate minimum quantity requirments.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;
            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json = array_merge($json, ['error' => ' Minimum order Q for some products is X ']);
                break;
            }
        }

        if (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) {
            $json = array_merge($json, ['error' => ['warning' => $this->language->get('error_stock')]]);
        }

        if (!$this->cart->hasProducts()) {
            $json = array_merge($json, ['error' => ['warning' => $this->language->get('text_empty')]]);
        }

        // address fields !
        $fields = [
            'firstname'         => '',
            'lastname'          => '',
            'email'             => '',
            'telephone'         => '',
            'company'           => '',
            'address_1'         => '',
            'address_2'         => '',
            'postcode'          => '',
            'city'              => '',
            'country_id'        => '',
            'zone_id'           => '',
            'customer_group_id' => '',
        ];

        $shipping_address = [];
        foreach ($fields as $key => $field) {
            $shipping_address[$key] = empty($this->request->post[$key]) ? "" : $this->request->post[$key];
        }

        $this->cart->setShippingAddress($shipping_address);
        $this->cart->setPaymentAddress($shipping_address);


        if ($this->cart->getTotal() < 10) {
            $json = array_merge($json, ['error' => ['warning' => $this->language->get('text_minimum_order')]]);
        }

        if ($render) {
            $this->response->setOutput(json_encode($json));
        } elseif ($json) {
            $this->session->data['message'] = 'ERROR in validation checkout line: ' . __LINE__;
            $this->session->data['flash_message'] = 'ERROR in validation checkout line: ' . __LINE__;
            $this->log->write('ERROR in validation: ');
            return $json;
        } else {
            return false;
        }
    }


    public function country($data = [])
    {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);


        if ($country_info) {

            $this->load->model('localisation/zone');

            $json = [
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => [], // $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status'],
            ];


            $this->cart->setShippingAddress($json);


        }

        // Pēc valsts maiņas, vajag atjaunot grozā pieejamās visas piegādes veidus!
        $this->cart->setShippingMethods();
        $this->cart->setPaymentMethods();


        $this->response->setOutput(json_encode($json));
    }

    //validate

    public function login_validate($data = [])
    {

        $this->load->language('checkout/checkout');

        $json = [];

        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->session->data['message'] = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
        }

        if (!$json) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $json['error']['warning'] = $this->language->get('error_login');
            }

            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ($customer_info && !$customer_info['approved']) {
                $json['error']['warning'] = $this->language->get('error_approved');
            }
        }

        if (!$json) {
            // unset($this->session->data['guest']);

            // Default Addresses
            $this->load->model('account/address');

            $address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

            if ($address_info) {

                if ($this->config->get('config_tax_customer') == 'payment') {
                    $this->cart->setPaymentAddress($this->model_account_address->getAddress($this->customer->getAddressId()));
                }

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->cart->setShippingAddress($this->model_account_address->getAddress($this->customer->getAddressId()));
                }

            } else {
                // šis būtu jāliek iekš Cart metodes, vai Checkout mateodes, cart "delete", kad ir veiksmīgi veikts pirkums.
                // UNSET session variables!

            }

            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        $this->response->setOutput(json_encode($json));
    }

    /*  public function guest_validate() {

          $this->load->language('checkout/checkout');


          $json = [];

          // Validate if customer is logged in.
          if ($this->customer->isLogged()) {
              $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
          }

          // Validate cart has products and has stock.
          if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {


          }

          // Check if guest checkout is available.
          if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
              $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
          }

          // pr( ddd());

          if (!$json) {



              if (isset($this->request->post['firstname'])
                  && ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32))) {
                  $json['error']['firstname'] = $this->language->get('error_firstname');
              }

              if (isset($this->request->post['email']) && ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
                  $json['error']['email'] = $this->language->get('error_email');
              }

              if (isset($this->request->post['telephone']) && ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
                  $json['error']['telephone'] = $this->language->get('error_telephone');
              }

              if (isset($this->request->post['address_1']) && ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128))) {
                  $json['error']['address_1'] = $this->language->get('error_address_1');
              }

              if (isset($this->request->post['city']) && ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128))) {
                  $json['error']['city'] = $this->language->get('error_city');
              }

              $this->load->model('localisation/country');
              $country_info = [];
              if (isset($this->request->post['country_id'])) {
                  $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
              }

              if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                  $json['error']['postcode'] = $this->language->get('error_postcode');
              }

              if (isset($this->request->post['country_id']) && $this->request->post['country_id'] == '') {
                  // $json['error']['country'] = $this->language->get('error_country');
                  $json['error']['country_id'] = $this->language->get('error_country');
              }

              //TODO: zone_id is required in checkout, used by many modules!
              // but must be set deprecated! Too many errors starts from this!
              if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                  // $json['error']['zone'] = $this->language->get('error_zone');
                  // $json['error']['zone_id'] = $this->language->get('error_zone');
              }
          }

          if (!$json && $this->request->post) {
              //  $this->session->data['account'] = 'guest';




              $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

              //            if ($country_info) {
              //                $this->session->data['payment_address']['country'] = $country_info['name'];
              //                $this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
              //                $this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
              //                $this->session->data['payment_address']['address_format'] = $country_info['address_format'];
              //            } else {
              //                $this->session->data['payment_address']['country'] = '';
              //                $this->session->data['payment_address']['iso_code_2'] = '';
              //                $this->session->data['payment_address']['iso_code_3'] = '';
              //                $this->session->data['payment_address']['address_format'] = '';
              //            }



              $this->load->model('localisation/zone');

              //TODO: fix this!
              $zone_info = [];
              if (!empty($this->request->post['zone_id'])) {
                  $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
              }

              //            if ($zone_info) {
              //                $this->session->data['payment_address']['zone'] = $zone_info['name'];
              //                $this->session->data['payment_address']['zone_code'] = $zone_info['code'];
              //            } else {
              //                $this->session->data['payment_address']['zone'] = '';
              //                $this->session->data['payment_address']['zone_code'] = '';
              //            }

              //            if (!empty($this->request->post['shipping_address'])) {
              //                $this->session->data['guest']['shipping_address'] = $this->request->post['shipping_address'];
              //            } else {
              //                $this->session->data['guest']['shipping_address'] = false;
              //            }

              // Default Payment Address
              if ($this->cart->getShippingAddress()) {


                  $fields = [
                      'firstname'  => '',
                      'lastname'   => '',
                      'email'      => '',
                      'telephone'  => '',
                      'company'    => '',
                      'address_1'  => '',
                      'address_2'  => '',
                      'postcode'   => '',
                      'city'       => '',
                      'country_id' => '',
                      'zone_id'    => '',
                  ];

                  $shipping_address = [];
                  foreach ($fields as $key => $field) {

                      if ($field == 'lastname') {
                          $shipping_address['lastname'] = empty($this->request->post['lastname']) ? "" : $this->request->post['lastname'];
                      } else {
                          $shipping_address[$key] = empty($this->request->post[$key]) ? "" : $this->request->post[$key];
                      }
                  }

                  prd($shipping_address);


              }

              // TODO; vajag
              // $this->cart->unset();
          }

          return $json;


      } */


    public function register_validate(&$data = [])
    {

        $this->load->language('checkout/checkout');

        $this->load->model('account/customer');

        $json = [];

        // Validate if customer is already logged out.
        if ($this->customer->isLogged()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            // $json['redirect'] = $this->url->link('checkout/cart');
            $this->session->data['message'] = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
        }

        if (!$json) {

            $this->load->model('account/customer');

            if (isset($this->request->post['firstname']) && ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32))) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }


            if (isset($this->request->post['email']) && ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if (isset($this->request->post['email']) && ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']))) {
                $json['error']['warning'] = $this->language->get('error_exists');
            }

            if (isset($this->request->post['telephone']) && ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            // Customer Group
            $this->load->model('account/customer_group');

            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'],
                    $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            $customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

            /*if ($customer_group) {
            // Company ID
            if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
            $json['error']['company_id'] = $this->language->get('error_company_id');
            }

            // Tax ID
            if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
            $json['error']['tax_id'] = $this->language->get('error_tax_id');
            }
            }*/


            if (isset($this->request->post['address_1']) && ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128))) {
                $json['error']['address_1'] = $this->language->get('error_address');
            }

            if (isset($this->request->post['city']) && ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128))) {
                $json['error']['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry(isset($this->request->post['country_id']) ? $this->request->post['country_id'] : 0);

            if ($country_info) {
                if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
                    $json['error']['postcode'] = $this->language->get('error_postcode');
                }


                // VAT Validation
                // https://github.com/pH-7/eu-vat-validator/
                // $this->load->helper('vat');

                // prd($this->vatValidation($this->request->post['tax_id']));

                if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
                    $json['error']['tax_id'] = $this->language->get('error_vat');
                }
            }


            if (!isset($this->request->post['country_id']) || $this->request->post['country_id'] == '') {
                $json['error']['country_id'] = $this->language->get('error_country');
            }

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                $json['error']['zone_id'] = $this->language->get('error_zone');
            }

            if (isset($this->request->post['register']) && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))) {
                $json['error']['password'] = $this->language->get('error_password');
            }

            if (isset($this->request->post['confirm']) && ($this->request->post['confirm'] != $this->request->post['password'])) {
                $json['error']['confirm'] = $this->language->get('error_confirm');
            }

            if ($this->config->get('config_account_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }
        }


        if (!$json) {
            //uncomment this


            $this->session->data['account'] = 'register';


            if (!$this->customer->isLogged()) {
                $this->session->data['checkout_customer_id'] = $customer_id = $this->model_account_customer->addCustomer($this->request->post);
                $this->session->data['checkout_customer_id'] = true;
            }

            $this->load->model('account/customer_group');

            $customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);


            if ($customer_group && !$customer_group['approval']) {
                $this->customer->login($this->request->post['email'], $this->request->post['password']);


                // Default Payment Address
                $this->load->model('account/address');

                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());

                if (!empty($this->request->post['shipping_address'])) {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

            } else {
                $json['redirect'] = $this->url->link('account/success');
            }

            unset($this->session->data['guest']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_id,
                'name'        => $this->request->post['firstname'] . ' ' . (!empty($this->request->post['lastname']) ? $this->request->post['lastname'] : ''),
            ];

            $this->model_account_activity->addActivity('register', $activity_data);


        }

        return $json;
    }

    public function payment_address_validate(&$data = [])
    {

        $this->load->language('checkout/checkout');

        $json = [];

        // Validate if customer is logged in.
        if (!$this->customer->isLogged()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->session->data['message'] = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
        }

        // // Validate minimum quantity requirments.
        // $products = $this->cart->getProducts();
        //
        // foreach ($products as $product) {
        //     $product_total = 0;
        //
        //     foreach ($products as $product_2) {
        //         if ($product_2['product_id'] == $product['product_id']) {
        //             $product_total += $product_2['quantity'];
        //         }
        //     }
        //
        //     if ($product['minimum'] > $product_total) {
        //         // $json['redirect'] = $this->url->link('checkout/cart');        //
        //         break;
        //     }
        // }


        if (!$json) {
            if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
                $this->load->model('account/address');

                if (empty($this->request->post['payment_address_id'])) {
                    $json['error']['warning'] = $this->language->get('error_address');
                } elseif (!in_array($this->request->post['payment_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                    $json['error']['warning'] = $this->language->get('error_address');
                }

                if (!$json) {
                    // Default Payment Address
                    $this->load->model('account/address');


                    $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['payment_address_id']);
                }
            } else {
                if (!isset($this->request->post['firstname']) || ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32))) {
                    $json['error']['firstname'] = $this->language->get('error_firstname');
                }

                //                if (!isset($this->request->post['lastname']) || ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32))) {
                //                    $json['error']['lastname'] = $this->language->get('error_lastname');
                //                }

                if (!isset($this->request->post['address_1']) || ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128))) {
                    $json['error']['address_1'] = $this->language->get('error_address_1');
                }

                if (!isset($this->request->post['city']) || ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32))) {
                    $json['error']['city'] = $this->language->get('error_city');
                }

                $this->load->model('localisation/country');

                if (isset($this->request->post['country_id'])) {
                    $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
                }

                if (isset($country_info) && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                    $json['error']['postcode'] = $this->language->get('error_postcode');
                }

                if (!isset($this->request->post['country_id']) || ($this->request->post['country_id'] == '')) {
                    $json['error']['country_id'] = $this->language->get('error_country');
                }

                //TODO: zone_id validation and system fix!
                //                if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                //                    $json['error']['zone_id'] = $this->language->get('error_zone');
                //                }

                // Custom field validation
                $this->load->model('account/custom_field');

                $custom_fields = $this->model_account_custom_field->getCustomFields(['filter_customer_group_id' => $this->config->get('config_customer_group_id')]);

                foreach ($custom_fields as $custom_field) {
                    if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                        $json['error']['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                    }
                }

                if (!$json) {
                    // Default Payment Address
                    // Customer - add address from cart !

                    // $this->load->model('account/address');
                    // $address_id = $this->model_account_address->addAddress($this->request->post);
                    // $this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);

                    $this->cart->setPaymentAddress($this->request->post);


                    /*
                    $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                    );

                    $this->model_account_activity->addActivity('address_add', $activity_data);					*/
                }
            }
        }


        return $json;
    }

    public function shipping_address_validate(&$data = [])
    {

        $this->load->language('checkout/checkout');

        $json = [];

        // Validate if customer is logged in.
        if (!$this->customer->isLogged()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            //$json['redirect'] = $this->url->link('checkout/cart');
            $this->session->data['message'] = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
        }

        // // Validate minimum quantity requirments.
        // $products = $this->cart->getProducts();
        //
        // foreach ($products as $product) {
        //     $product_total = 0;
        //
        //     foreach ($products as $product_2) {
        //         if ($product_2['product_id'] == $product['product_id']) {
        //             $product_total += $product_2['quantity'];
        //         }
        //     }
        //
        //     if ($product['minimum'] > $product_total) {
        //         // $json['redirect'] = $this->url->link('checkout/cart');
        //
        //         break;
        //     }
        // }

        if (!$json) {

            if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address'] == 'existing') {
                $this->load->model('account/address');

                if (empty($this->request->post['shipping_address_id'])) {
                    $json['error']['warning'] = $this->language->get('error_address');
                } elseif (!in_array($this->request->post['shipping_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                    $json['error']['warning'] = $this->language->get('error_address');
                }

                if (!$json) {
                    $this->session->data['shipping_address_id'] = $this->request->post['shipping_address_id'];

                    // Default Shipping Address
                    $this->load->model('account/address');

                    $address_info = $this->model_account_address->getAddress($this->request->post['shipping_address_id']);

                    if ($address_info) {
                        $this->session->data['shipping_country_id'] = $address_info['country_id'];
                        $this->session->data['shipping_zone_id'] = $address_info['zone_id'];
                        $this->session->data['shipping_postcode'] = $address_info['postcode'];
                    } else {
                        unset($this->session->data['shipping_country_id']);
                        unset($this->session->data['shipping_zone_id']);
                        unset($this->session->data['shipping_postcode']);
                    }

                }
            }

            if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address'] == 'new') {
                if ((utf8_strlen($this->request->post['shipping_firstname']) < 1) || (utf8_strlen($this->request->post['shipping_firstname']) > 32)) {
                    $json['error']['shipping_firstname'] = $this->language->get('error_firstname');
                }

                if ((utf8_strlen($this->request->post['shipping_lastname']) < 1) || (utf8_strlen($this->request->post['shipping_lastname']) > 32)) {
                    $json['error']['shipping_lastname'] = $this->language->get('error_lastname');
                }

                if ((utf8_strlen($this->request->post['shipping_address_1']) < 3) || (utf8_strlen($this->request->post['shipping_address_1']) > 128)) {
                    $json['error']['shipping_address_1'] = $this->language->get('error_address_1');
                }

                if ((utf8_strlen($this->request->post['shipping_city']) < 2) || (utf8_strlen($this->request->post['shipping_city']) > 128)) {
                    $json['error']['shipping_city'] = $this->language->get('error_city');
                }

                $this->load->model('localisation/country');

                $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

                if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2) || (utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
                    $json['error']['shipping_postcode'] = $this->language->get('error_postcode');
                }

                if ($this->request->post['shipping_country_id'] == '') {
                    $json['error']['shipping_country'] = $this->language->get('error_country');
                }

                if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
                    $json['error']['shipping_zone'] = $this->language->get('error_zone');
                }

                if (!$json) {
                    // Default Shipping Address
                    $this->load->model('account/address');
                    $_shipping_address = [];
                    foreach ($this->request->post as $key => $value) {
                        if (strpos($key, 'shipping_') !== false) {
                            $_shipping_address[str_replace('shipping_', '', $key)] = $value;
                        }
                    }

                    $this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($_shipping_address);
                    $this->session->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
                    $this->session->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
                    $this->session->data['shipping_postcode'] = $this->request->post['shipping_postcode'];

                }
            }
        }

        return $json;
    }

    public function shipping_method_set()
    {
        $json = [];

        if (!isset($this->request->request['shipping_method'])) {

            prd($json['error']['warning']);

            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {

            $shipping = explode('.', $this->request->request['shipping_method']);


            if (!isset($shipping[0]) || !isset($shipping[1])) {

                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }

        if (!$json) {

            $this->cart->setShippingMethods();

            $this->cart->setShippingMethod($this->cart->getShippingMethod($this->request->request['shipping_method']));
            $this->cart->setComment((isset($this->request->request['comment'])) ? strip_tags($this->request->request['comment']) : '');
        }


        $this->response->setOutput(json_encode($json));

    }

    private function shipping_method_validate(&$data = [])
    {

        $this->load->language('checkout/checkout');
        $json = [];

        if (!$this->cart->getShippingMethod()) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        }

        return $json;

    }


    public function payment_method_validate(&$data = [])
    {
        $this->load->language('checkout/checkout');

        $json = [];

        // Validate if payment address has been set.
        $this->load->model('account/address');


        //        if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
        //            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        //        } elseif (isset($this->session->data['guest'])) {
        //             = $this->session->data['guest']['payment'];
        //        } else {
        //            $payment_address = $this->model_account_address->getAddress(0);
        //        }


        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            //$json['redirect'] = $this->url->link('checkout/cart');
            $this->session->data['message'] = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
        }

        // // Validate minimum quantity requirments.
        // $products = $this->cart->getProducts();
        //
        // foreach ($products as $product) {
        //     $product_total = 0;
        //
        //     foreach ($products as $product_2) {
        //         if ($product_2['product_id'] == $product['product_id']) {
        //             $product_total += $product_2['quantity'];
        //         }
        //     }
        //
        //     if ($product['minimum'] > $product_total) {
        //         // $json['redirect'] = $this->url->link('checkout/cart');
        //
        //         break;
        //     }
        // }

        if (!$json) {
            if (!isset($this->request->post['payment_method'])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                //				error_log(print_r($this->session->data['payment_methods'],1));
                $json['error']['warning'] = $this->language->get('error_payment');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }

            if (!$json) {

                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                $this->session->data['comment'] = (isset($this->request->post['comment'])) ? strip_tags($this->request->post['comment']) : '';
            }
            if (!isset($this->request->post['payment_method'])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                //				error_log(print_r($this->session->data['payment_methods'],1));
                $json['error']['warning'] = $this->language->get('error_payment');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }

            if (!$json) {

                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                $this->session->data['comment'] = (isset($this->request->post['comment'])) ? strip_tags($this->request->post['comment']) : '';
            }
        }

        // prd($json);
        return $json;
    }


    public function guest($render = false, &$data = [])
    {
        $this->load->language('checkout/checkout');

        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_account'] = $this->language->get('text_your_account');
        $data['text_your_address'] = $this->language->get('text_your_address');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        if ($this->language->get('entry_company_id') != 'entry_company_id') {
            $data['entry_company_id'] = $this->language->get('entry_company_id');
        }
        if ($this->language->get('entry_tax_id') != 'entry_tax_id') {
            $data['entry_tax_id'] = $this->language->get('entry_tax_id');
        }
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_shipping'] = $this->language->get('entry_shipping');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->session->data['guest']['firstname'])) {
            $data['firstname'] = $this->session->data['guest']['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->session->data['guest']['lastname'])) {
            $data['lastname'] = $this->session->data['guest']['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->session->data['guest']['email'])) {
            $data['email'] = $this->session->data['guest']['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->session->data['guest']['telephone'])) {
            $data['telephone'] = $this->session->data['guest']['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->session->data['guest']['fax'])) {
            $data['fax'] = $this->session->data['guest']['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->session->data['guest']['payment']['company'])) {
            $data['company'] = $this->session->data['guest']['payment']['company'];
        } else {
            $data['company'] = '';
        }

        $this->load->model('account/customer_group');

        $data['customer_groups'] = [];

        if (is_array($this->config->get('config_customer_group_display'))) {
            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups as $customer_group) {
                if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $data['customer_groups'][] = $customer_group;
                }
            }
        }

        // prd($data['customer_groups']);


        if (isset($this->session->data['guest']['customer_group_id'])) {
            $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        // Company ID
        if (isset($this->session->data['guest']['payment']['company_id'])) {
            $data['company_id'] = $this->session->data['guest']['payment']['company_id'];
        } else {
            $data['company_id'] = '';
        }

        // Tax ID
        if (isset($this->session->data['guest']['payment']['tax_id'])) {
            $data['tax_id'] = $this->session->data['guest']['payment']['tax_id'];
        } else {
            $data['tax_id'] = '';
        }

        if (isset($this->session->data['guest']['payment']['address_1'])) {
            $data['address_1'] = $this->session->data['guest']['payment']['address_1'];
        } else {
            $data['address_1'] = '';
        }

        if (isset($this->session->data['guest']['payment']['address_2'])) {
            $data['address_2'] = $this->session->data['guest']['payment']['address_2'];
        } else {
            $data['address_2'] = '';
        }

        if (isset($this->session->data['guest']['payment']['postcode'])) {
            $data['postcode'] = $this->session->data['guest']['payment']['postcode'];
        } elseif (isset($this->session->data['shipping_postcode'])) {
            $data['postcode'] = $this->session->data['shipping_postcode'];
        } else {
            $data['postcode'] = '';
        }

        if (isset($this->session->data['guest']['payment']['city'])) {
            $data['city'] = $this->session->data['guest']['payment']['city'];
        } else {
            $data['city'] = '';
        }

        if (isset($this->session->data['guest']['payment']['country_id']) && $this->session->data['guest']['payment']['country_id']) {
            $data['country_id'] = $this->session->data['guest']['payment']['country_id'];
        } elseif (isset($this->session->data['shipping_country_id']) && $this->session->data['shipping_country_id']) {
            $data['country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $data['country_id'] = 0; // $this->config->get('config_country_id');
        }


        if (isset($this->session->data['guest']['payment']['zone_id'])) {
            $data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];
        } elseif (isset($this->session->data['shipping_zone_id'])) {
            $data['zone_id'] = $this->session->data['shipping_zone_id'];
        } else {
            $data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['shipping_required'] = $this->cart->hasShipping();

        if (isset($this->session->data['guest']['shipping_address'])) {
            $data['shipping_address'] = $this->session->data['guest']['shipping_address'];
        } else {
            $data['shipping_address'] = true;
        }

        if ($render !== false) {

            $this->response->setOutput($this->load->view('checkout/guest', $data));


        }
    }

    public
    function login(
        $render = false,
        &$data = []
    ) {
        $this->load->language('checkout/checkout');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');

        $data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = 'register';
        }

        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if ($render !== false) {

            $this->response->setOutput($this->load->view('checkout/login', $data));


        }
    }

    public function cart($render = true, &$data = [])
    {


        // return $this->load->controller('checkout/cart/getCartTable');

        if ($this->request->post) {
            $this->validate($this->request->post);
        }
        // $this->log->write($this->session->data('shipping_address'));


        // $cart_data = $this->load->language('checkout/checkout');
        // $cart_data = array_merge($cart_data, $this->load->controller('checkout/cart/getCartTableData'));
        $cart_data = $this->load->controller('checkout/cart/getCartTableData');

        // prd($cart_data);
        $cart_output = $this->load->view('checkout/easy_cart', $cart_data);

        // prd($cart_output);

        // hack: IF cart is called from Engine, "render" wil be an empty array ... but we need to render! :(
        if ($render || is_array($render)) {
            return $this->response->setOutput($cart_output);
        } else {
            return $cart_output;
        }


    }


    public function confirm($render = true, &$data = [])
    {


        // WIP
        // unset($this->session->data['order_id']) ;

        // $this->payment_method_validate();


        // JA VALIDĒJAS korekti, tad netiks atgriezts NEKAS,
        // bet jha nevalidējas, tad būs error masīvs!


        if ($this->validate($this->request->post, false))// tukšs masivs, un FALSE - jo gribam RETURN nevis render oiutput !
        {

            prd('NOT VALIDATED! ');
        }

        $data['payment'] = '';
        $data['text_cart'] = $this->language->get('text_cart');

        // Validate if payment address has been set.

        if ($this->cart->hasShipping() && !$this->cart->getShippingAddress()) {
            prd();
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }
        if (!$this->cart->getPaymentAddress()) {
            prd();
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $message = " NOT ENOUGH PRODUCTS IN STOCK ! ( error: " . __LINE__ . " ) ";
            $this->session->data['message'] = $message;
            prd($message);
        }


        $order_data = [];

        // Totals, Products
        $order_data['totals'] = $this->cart->getTotals_azon();

        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');

        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            $order_data['store_url'] = HTTP_SERVER;
        }


        if (isset($_POST) && !empty($_POST)) {

            $this->payment_method();


            //           if ($this->customer->isLogged()) {
            //                $this->load->model('account/customer');
            //                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            //                $customer['customer_id'] = $this->customer->getId();
            //               $customer['customer_group_id'] = $customer_info['customer_group_id'];
            //               $customer['firstname'] = $customer_info['firstname'];
            //               $customer['lastname'] = $customer_info['lastname'];
            //               $customer['email'] = $customer_info['email'];
            //               $customer['telephone'] = $customer_info['telephone'];
            //               $customer['fax'] = $customer_info['fax'];

            //            } elseif (isset($this->session->data['guest'])) {
            //                $customer['customer_id'] = 0;
            //                $customer['customer_group_id'] = isset($this->session->data['guest']['customer_group_id']) ? $this->session->data['guest']['customer_group_id'] : $this->config->get('config_customer_group_id');;
            //                $customer['firstname'] = isset($this->session->data['guest']['firstname']) ? $this->session->data['guest']['firstname'] : '';
            //                $customer['lastname'] = isset($this->session->data['guest']['lastname']) ? $this->session->data['guest']['lastname'] : '';
            //                $customer['email'] = isset($this->session->data['guest']['email']) ? $this->session->data['guest']['email'] : '';
            //                $customer['telephone'] = isset($this->session->data['guest']['telephone']) ? $this->session->data['guest']['telephone'] : '';
            //                $customer['fax'] = isset($this->session->data['guest']['fax']) ? $this->session->data['guest']['fax'] : '';

            //            }


            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            if ($customer_info) {
                $order_data['customer_id'] = $this->customer->getId();
                $order_data['customer_group_id'] = $customer_info['customer_group_id'];
            } else {
                $order_data['customer_id'] = 0;
                $order_data['customer_group_id'] = 0;
            }


            // WIP
            // $order_data['customer'] = $this->cart->getShippingAddress();


            // WIP: šis ir korekti?
            $order_data['shipping_address'] = $this->cart->getShippingAddress();
            $order_data['payment_address'] = $this->cart->getPaymentAddress();

            // JA ir norādīta otra adrese, tad mēs ar to pārrakstām payment adresi.

            // pr($this->cart->getAddress2());

            foreach ($this->cart->getAddress2() as $key => $val) {
                if ($val > '') {
                    $order_data['payment_address'][$key] = $val;
                }
            }

            if ($this->cart->getAddress2()) {
                // tātad, ir billing_address_details !
                $order_data['payment_address_different'] = 1; // TODO: Tizli, a ko darīt ....
            } else {
                $order_data['payment_address_different'] = 0;
            }


            // prd( $this->cart->getShippingAddress() ) ;

            $order_data['email'] = $this->cart->getShippingAddress()['email'];

            if (isset($this->session->data['payment_method']['title'])) {
                $order_data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $order_data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $order_data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $order_data['payment_code'] = '';
            }

            $order_data = array_merge($order_data, $this->cart->getShippingAddress());
            // mums vajag kā masīvu, jo addOrder metode pievieno arī šos iekš Totals tabulas!!! (tas ir labo, jo tad pagaidām tas
            // nekur netraucē!
            $order_data['shipping_method'] = $this->cart->getShippingMethod();
            $order_data['shipping_code'] = $this->cart->getShippingMethod()['code'];
            $order_data['products'] = $this->cart->getProducts();

            // Gift Voucher
            $order_data['vouchers'] = [];

            //            if (!empty($this->session->data['vouchers'])) {
            //                foreach ($this->session->data['vouchers'] as $voucher) {
            //                    $order_data['vouchers'][] = [
            //                        'description'      => $voucher['description'],
            //                        'code'             => substr(md5(mt_rand()), 0, 10),
            //                        'to_name'          => $voucher['to_name'],
            //                        'to_email'         => $voucher['to_email'],
            //                        'from_name'        => $voucher['from_name'],
            //                        'from_email'       => $voucher['from_email'],
            //                        'voucher_theme_id' => $voucher['voucher_theme_id'],
            //                        'message'          => $voucher['message'],
            //                        'amount'           => $voucher['amount'],
            //                    ];
            //                }
            //            }

            $order_data['comment'] = $this->session->data['comment'];
            $order_data['total'] = $this->cart->getCartTotal();

            //
            //            if (isset($this->request->cookie['tracking'])) {
            //                $order_data['tracking'] = $this->request->cookie['tracking'];
            //
            //                $subtotal = $this->cart->getSubTotal();
            //
            //                // Affiliate
            //                $this->load->model('affiliate/affiliate');
            //
            //                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
            //
            //                if ($affiliate_info) {
            //                    $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
            //                    $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            //                } else {
            //                    $order_data['affiliate_id'] = 0;
            //                    $order_data['commission'] = 0;
            //                }
            //
            //                // Marketing
            //                $this->load->model('checkout/marketing');
            //
            //                $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);
            //
            //                if ($marketing_info) {
            //                    $order_data['marketing_id'] = $marketing_info['marketing_id'];
            //                } else {
            //                    $order_data['marketing_id'] = 0;
            //                }
            //            } else {
            //                $order_data['affiliate_id'] = 0;
            //                $order_data['commission'] = 0;
            //                $order_data['marketing_id'] = 0;
            //                $order_data['tracking'] = '';
            //            }

            // TODO: Šis ir jāadministrē, ja tiek norādīt skorekti.
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';


            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
            $order_data['currency_code'] = $this->session->data['currency'];
            $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'];


            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $order_data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $order_data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $order_data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            // TODO: Vajag, lai šis būtu definēts, citādi ir mysqli excape errors tukšai vērtībai!

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

            // prd($this->session->data['order_id']);


            $data['text_recurring_item'] = $this->language->get('text_recurring_item');
            $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
        }


        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $this->load->model('tool/upload');

        $data['products'] = $this->cart->getProducts();
        // 'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),

        // Gift Voucher
        $data['vouchers'] = [];

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $data['vouchers'][] = [
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount']),
                ];
            }
        }

        $data['totals'] = [];

        foreach ($order_data['totals'] as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
            ];
        }

        if ($render !== false) {
            $code = explode('.', $this->session->data['payment_method']['code'])[0];


            if ($code) {
                $data['payment'] = $this->load->controller('extension/payment/' . $code);
            } else {
                $data['payment'] = '';
            }

            $this->response->setOutput($this->load->view('checkout/easy_confirm', $data));
        }

    }

    public function vatValidation(
        $vat = ''
    ) {

        $vat = $this->request->get('vat');

        // prd($vat);
        //check cache at first!
        if (!$vat || !gettype($vat) == 'string') {
            return false;
        }

        if ($this->cache->get('validate_' . $vat)) {
            return true;
        }

        try {
            $oVatValidator = new Validator(new Europa, substr($vat, 2), substr($vat, 0, 2));
        } catch (Exception $e) {
            $this->log->write($e->getMessage());
            return false;
        }

        if ($oVatValidator->check()) {
            $this->cache->set('validate_' . $vat, $oVatValidator->getRequestDate());
            return true;
            $sRequestDate = $oVatValidator->getRequestDate();
            // Optional, format the date
            //$sFormattedRequestDate = (new DateTime)->format('d-m-Y');

            //echo 'Business Name: ' . $oVatValidator->getName() . '<br />';
            //echo 'Address: ' . $oVatValidator->getAddress() . '<br />';
            //echo 'Request Date: ' . $sFormattedRequestDate . '<br />';
            //echo 'Member State: ' . $oVatValidator->getCountryCode() . '<br />';
            //echo 'VAT Number: ' . $oVatValidator->getVatNumber() . '<br />';
        } else {
            $this->cache->set('validate_' . $vat, false);
            echo 'Invalid VAT number';
        }
    }

    public function customfield()
    {
        $json = [];
        $this->load->model('account/custom_field');
        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'],
                $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
        pr($custom_fields);
        foreach ($custom_fields as $custom_field) {
            $json[] = [
                'custom_field_id' => $custom_field['custom_field_id'],
                'required'        => $custom_field['required'],
            ];
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function processPostData()
    {


        return;

        $this->load->model('localisation/country');

        $address_fields = [
            'firstname'  => [],
            'telephone'  => [],
            'address_1'  => [],
            'city'       => [],
            'postcode'   => [],
            'email'      => [],
            'country_id' => [],
            'zone_id'    => [],

        ];


        $this->cart->setPaymentAddress($this->session->data('shipping_address'));


    }


    /*
     *
     * otrās adreses validēšana.
     * */
    public function address2_validate()
    {
        $json = [];
        $this->load->model('account/custom_field');

        if ($this->request->post('billing_address_details')) {

            $this->cart->setAddress2($this->request->post('address2'));

            // prd($this->cart->getAddress2());

        } else {
            $this->cart->clearAddress2();
        }
        return [];
    }

    /*
     * Tikai redigesana groza atlikumam!
     */

    public function edit_only()
    {


        // Update
        if (!empty($this->request->post['quantity'])) {
            foreach ($this->request->post['quantity'] as $key => $value) {
                $this->cart->update($key, $value);
            }

            $this->session->data['success'] = $this->language->get('text_remove');
        }


        $this->response->setOutput(json_encode(['success' => 'OK']));
    }


    public function validateCoupon()
    {
        $this->load->language('checkout/checkout');
        $this->load->language('extension/total/coupon');


        $json = [];

        if (!isset($this->request->post['coupon']) || empty($this->request->post['coupon'])) {
            $this->request->post['coupon'] = '';
            $this->session->data['coupon'] = '';
        }

        $this->load->model('extension/total/coupon');

        if ($this->request->post['coupon'] == '') {
            unset($this->session->data['coupon']);

            $json['success'] = $this->language->get('text_coupon_removed');
        } else {
            $coupon_info = $this->model_extension_total_coupon->getCoupon($this->request->post['coupon']);

            if (!$coupon_info) {
                $json['error']['warning'] = $this->language->get('error_coupon');
            }

            if (!$json) {
                $this->session->data['coupon'] = $this->request->post['coupon'];

                $subtotal = $this->cart->getSubTotal();

                $coupon = $this->model_extension_total_coupon->getCoupon($this->session->data['coupon']);
                if ($coupon['type'] == 'P') {
                    $json['coupon_discount'] = round($subtotal * ($coupon['discount'] / 100), 2);
                } elseif ($coupon['type'] == 'F') {
                    $json['coupon_discount'] = round($coupon['discount'], 2);
                }

                $json['add_coupon'] = true;
                $json['success'] = sprintf($this->language->get('text_coupon'), $coupon['name']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }


}
