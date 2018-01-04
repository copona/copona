<?php
class ControllerCheckoutShippingMethod extends Controller {
    public function __construct($params) {
        parent::__construct($params);
        $this->load->model('extension/extension');
        $this->load->model('localisation/location');
    }

    public function index() {
        $data = $this->load->language('checkout/checkout');

        /*
        $this->session->data['shipping_method_group'] = '';
        if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
            $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
        } elseif (isset($this->request->post['zone_id'])) {
            $shipping_address['zone_id'] = (int)$this->request->post['zone_id'];
            $this->session->data['guest']['shipping']['zone_id'] = (int)$this->request->post['zone_id'];
            $shipping_address['country_id'] = (int)$this->request->post['country_id'];
            $this->session->data['guest']['shipping']['country_id'] = (int)$this->request->post['country_id'];
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['shipping'])) {
            $shipping_address = $this->session->data['guest']['shipping'];
        } else {
            $shipping_address['country_id'] = $this->config->get('config_country_id');
            $shipping_address['zone_id'] = $this->config->get('config_zone_id');
        }
        */
        if(empty($this->session->data['shipping_address']) ) {
            $this->session->data['shipping_address'] = $this->model_localisation_location
                ->getStoreAddress();
        };

        $this->load->model('extension/extension');

        if (isset($this->session->data['shipping_address'])) {

            if(empty($this->session->data['shipping_address']['country_id']) ) {
                $this->session->data['shipping_address']['country_id'] = Config::get('config_country_id');
            }

            if(!empty($this->request->post['country_id']) ) {
                $this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
            }

            if(!empty($this->request->post['zone_id']) && (int)$this->request->post['zone_id'] ) {
                $this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
            } elseif (empty($this->session->data['shipping_address']['zone_id'])) {
                $this->session->data['shipping_address']['zone_id'] = 0;
            }

            // Shipping Methods
            $method_data = array();

            $results = $this->model_extension_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/shipping/' . $result['code']);

                    $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

                    if ($quote) {
                        $method_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => $quote['quote'],
                            'sub_quote'      => (!empty($quote['sub_quote']) && $quote['sub_quote'] ? $quote['sub_quote'] : ''),
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error']
                        );
                    }
                }
            }

            $sort_order = array();

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            //prd($method_data); 

            $this->session->data['shipping_methods'] = $method_data;
        }

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['shipping_methods'])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $data['shipping_methods'] = array();
        }

        if (isset($this->session->data['shipping_method']['code'])) {
            $data['code'] = $this->session->data['shipping_method']['code'];
        } else {
            $data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        $this->response->setOutput($this->load->view('checkout/shipping_method', $data));
    }

    public function save() {
        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', true);
        }

        // Validate if shipping address has been set.
        if (!isset($this->session->data['shipping_address'])) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', true);
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!isset($this->request->post['shipping_method'])) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }

        if (!$json) {
            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

            $this->session->data['comment'] = strip_tags($this->request->post['comment']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /* COPONA */
    public function getZonesByCountryId() {
        $json = array();
        $this->load->model('localisation/zone');
        $json = $this->model_localisation_zone->getZonesByCountryId($this->request->post['country_id']);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}