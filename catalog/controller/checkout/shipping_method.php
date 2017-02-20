<?php
class ControllerCheckoutShippingMethod extends Controller {

    public function index() {
        $this->language->load('checkout/checkout');
        $this->load->model('account/address');

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

        if (!empty($shipping_address)) {

            // Shipping Methods
            $quote_data = array();
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/shipping/' . $result['code']);
                    $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);
                    if ($quote) {
                        $quote_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error']
                        );
                        if (isset($quote['group_title'])) {
                            $quote_data[$result['code']]['group_title'] = $quote['group_title'];
                        }
                    }
                }
            }

            //Sort shipping methods
            $this->array_sort_by_column($quote_data, 'sort_order');

            $this->session->data['shipping_methods'] = $quote_data;

            if (!isset($this->session->data['shipping_method'])) {
                reset($quote_data);
                $first_key1 = key($quote_data);
                if ($quote_data[$first_key1]['quote']) {
                    reset($quote_data[$first_key1]['quote']);
                    $first_key2 = key($quote_data[$first_key1]['quote']);
                    $this->session->data['shipping_method'] = $quote_data[$first_key1]['quote'][$first_key2];
                }
            } else {
                $shipping = explode('.', (isset($this->session->data['shipping_method'][1]) ? $this->session->data['shipping_method'][1]['code'] : $this->session->data['shipping_method']['code']));
                if ($this->session->data['shipping_methods'][$shipping[0]]['quote']) {
                    $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                }
            }
        }

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
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

        if (isset($this->session->data['shipping_method_group'])) {
            $data['group'] = $this->session->data['shipping_method_group'];
        } else {
            $data['group'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        $this->response->setOutput($this->load->view('checkout/shipping_method', $data));
    }

    public function validate() {
        $this->language->load('checkout/checkout');
        $json = array();
        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if shipping address has been set.
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
            $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['shipping'])) {
            $shipping_address = $this->session->data['guest']['shipping'];
        } else {
            $shippin_address = array();
        }

        if (empty($shipping_address)) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('order/cart');
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
                $json['redirect'] = $this->url->link('order/cart');

                break;
            }
        }

        if (!$json) {

            if (!isset($this->request->post['shipping_method'])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            } else {
                $shipping = explode('.', $this->request->post['shipping_method']);

                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

                if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                    $json['error']['warning'] = $this->language->get('error_shipping');
                }
            }

            if (!$json) {

                $shipping = explode('.', $this->request->post['shipping_method']);
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                $this->session->data['comment'] = strip_tags($this->request->post['comment']);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    public function getZonesByCountryId() {
        $json = array();
        $this->load->model('localisation/zone');
        $json = $this->model_localisation_zone->getZonesByCountryId($this->request->post['country_id']);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
?>