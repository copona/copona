<?php
class ControllerCheckoutConfirm extends Controller {

    public function index() {

        $redirect = '';

        $data = array_merge($this->load->language('checkout/checkout'), $this->load->language('order/order'));
        $this->document->setTitle($this->language->get('heading_title2'));

        if ($this->cart->hasShipping()) {

            // Validate if shipping address has been set.
            $this->load->model('account/address');

            $shipping_address = $this->session->data['guest']['shipping_address'];

            if (empty($shipping_address)) {
                $redirect = $this->url->link('checkout/checkout/guest', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment address has been set.
        $this->load->model('account/address');

        // Validate if payment method has been set.

        if (empty($this->session->data['payment_method'])) {
            $this->flash->error($this->language->get('error_payment'));
            $redirect = $this->url->link('checkout/checkout/guest', '', 'SSL');
        }

        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $data['error_warning']['error_stock'] = $this->language->get('error_stock');
        } elseif (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (!empty($this->session->data['error_payment_failure'])) {
            $data['error_warning']['error_payment_failure'] = $this->session->data['error_payment_failure'];
            unset($this->session->data['error_payment_failure']);
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
                break;
            }
        }

        if (!$redirect) {

            $this->language->load('checkout/checkout');

            $data1 = array();
            $data1['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $data1['store_id'] = $this->config->get('config_store_id');
            $data1['store_name'] = $this->config->get('config_name');

            $data1['serial'] = [];
            if(!empty($this->session->data['guest']['serial'])){
                $data1['serial'] = $this->session->data['guest']['serial'];
            }

            if ($data1['store_id']) {
                $data1['store_url'] = $this->config->get('config_url');
            } else {
                $data1['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $data1['customer_id'] = $this->customer->getId();
                $data1['customer_group_id'] = $customer_info['customer_group_id'];
                $data1['firstname'] = $customer_info['firstname'];
                $data1['lastname'] = $customer_info['lastname'];
                $data1['email'] = $customer_info['email'];
                $data1['telephone'] = $customer_info['telephone'];
                $data1['fax'] = $customer_info['fax'];
                $data1['custom_field'] = unserialize($customer_info['custom_field']);
                $this->load->model('account/address');
            } elseif (isset($this->session->data['guest'])) {
                $data1['customer_id'] = 0;
                $data1['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                $data1['firstname'] = $this->session->data['guest']['firstname'];
                $data1['lastname'] = $this->session->data['guest']['lastname'];

                $data1['email'] = $this->session->data['guest']['email'];
                $data1['telephone'] = $this->session->data['guest']['telephone'];
                $data1['fax'] = $this->session->data['guest']['fax'];
            }

            $payment_address = $this->session->data['guest']['payment_address'];

            $data1['payment_firstname'] = $payment_address['firstname'];
            $data1['payment_lastname'] = $payment_address['lastname'];
            $data1['payment_company'] = $payment_address['company'];
            $data1['payment_company_id'] = $payment_address['company_id'];
            $data1['payment_tax_id'] = $payment_address['tax_id'];
            $data1['payment_address_1'] = $payment_address['address_1'];
            $data1['payment_address_2'] = $payment_address['address_2'];
            $data1['payment_city'] = $payment_address['city'];
            $data1['payment_postcode'] = $payment_address['postcode'];
            $data1['payment_zone'] = $payment_address['zone'];
            $data1['payment_zone_id'] = $payment_address['zone_id'];
            $data1['payment_country'] = $payment_address['country'];
            $data1['payment_country_id'] = $payment_address['country_id'];
            $data1['payment_address_format'] = $payment_address['address_format'];
            $data1['customer_group_id'] = $payment_address['customer_group_id'];
            $data1['company_name'] = $payment_address['company_name'];
            $data1['reg_num'] = $payment_address['reg_num'];
            $data1['vat_num'] = $payment_address['vat_num'];
            $data1['bank_name'] = $payment_address['bank_name'];
            $data1['bank_code'] = $payment_address['bank_code'];
            $data1['bank_account'] = $payment_address['bank_account'];
            $data1['address_2'] = $payment_address['address_2'];

            if (isset($this->session->data['payment_method']['title'])) {
                $data1['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $data1['payment_method'] = '';
            }
            //pr($this->session->data['shipping_method']);
            if (isset($this->session->data['payment_method']['code'])) {
                $data1['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $data1['payment_code'] = '';
            }

            if ($this->cart->hasShipping()) {
                if (!$this->customer->isLogged() && isset($this->session->data['guest'])) {
                    $shipping_address = $this->session->data['guest']['shipping_address'];
                }
                $data1['shipping_firstname'] = $shipping_address['firstname'];
                $data1['shipping_lastname'] = $shipping_address['lastname'];
                $data1['shipping_company'] = $shipping_address['company'];
                $data1['shipping_address_1'] = $shipping_address['address_1'];
                $data1['shipping_address_2'] = (isset($shipping_address['address_2']) ? $shipping_address['address_2'] : '');
                $data1['shipping_city'] = $shipping_address['city'];
                $data1['shipping_postcode'] = $shipping_address['postcode'];
                $data1['shipping_zone'] = $shipping_address['zone'];
                $data1['shipping_zone_id'] = $shipping_address['zone_id'];
                $data1['shipping_country'] = $shipping_address['country'];
                $data1['shipping_country_id'] = $shipping_address['country_id'];
                $data1['shipping_address_format'] = $shipping_address['address_format'];

                if (isset($this->session->data['shipping_method']['title'])) {
                    $data1['shipping_method'] = $this->session->data['shipping_method']['title'];
                } else {
                    $data1['shipping_method'] = '';
                }

                if (isset($this->session->data['shipping_method']['code'])) {
                    $data1['shipping_code'] = $this->session->data['shipping_method']['code'];
                } else {
                    $data1['shipping_code'] = '';
                }


            } else {
                $data1['shipping_firstname'] = '';
                $data1['shipping_lastname'] = '';
                $data1['shipping_company'] = '';
                $data1['shipping_address_1'] = '';
                $data1['shipping_address_2'] = '';
                $data1['shipping_city'] = '';
                $data1['shipping_postcode'] = '';
                $data1['shipping_zone'] = '';
                $data1['shipping_zone_id'] = '';
                $data1['shipping_country'] = '';
                $data1['shipping_country_id'] = '';
                $data1['shipping_address_format'] = '';
                $data1['shipping_method'] = '';
                $data1['shipping_code'] = '';
            }

            $data['shipping_address_location'] = '';
            if(!empty($this->session->data['guest']['shipping_address'])) {
                $data['shipping_address_location'] .= $this->session->data['guest']['shipping_address']['country'] ? $this->session->data['guest']['shipping_address']['country'] . ", " : '';
                $data['shipping_address_location'] .= $this->session->data['guest']['shipping_address']['zone'] ? $this->session->data['guest']['shipping_address']['zone'] . ", " : '';
                $data['shipping_address_location'] .= $this->session->data['guest']['shipping_address']['city'] ? $this->session->data['guest']['shipping_address']['city'] . ", " : '';
                $data['shipping_address_location'] .= $this->session->data['guest']['shipping_address']['address_1'] ? $this->session->data['guest']['shipping_address']['address_1'] . ", " : '';
                $data['shipping_address_location'] .= $this->session->data['guest']['shipping_address']['postcode'] ? $this->session->data['guest']['shipping_address']['postcode'] . ", " : '';

            }

            $product_data = $this->cart->getProducts();

            // Gift Voucher
            $voucher_data = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $voucher_data[] = array(
                        'description'      => $voucher['description'],
                        'code'             => substr(md5(mt_rand()), 0, 10),
                        'to_name'          => $voucher['to_name'],
                        'to_email'         => $voucher['to_email'],
                        'from_name'        => $voucher['from_name'],
                        'from_email'       => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message'          => $voucher['message'],
                        'amount'           => $voucher['amount']
                    );
                }
            }

            $data1['products'] = $product_data;
            $data1['vouchers'] = $voucher_data;
            $data1['totals'] = $this->cart->getTotals_azon();
            $data1['comment'] = empty($this->session->data['comment']) ? '' : $this->session->data['comment'];
            $data1['total'] = $this->cart->getTotal();


            if (isset($this->request->cookie['tracking'])) {
                $data1['tracking'] = $this->request->cookie['tracking'];
                $subtotal = $this->cart->getSubTotal();

                // Affiliate
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

                if ($affiliate_info) {
                    $data1['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $data1['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $data1['affiliate_id'] = 0;
                    $data1['commission'] = 0;
                }

                // Marketing
                $this->load->model('checkout/marketing');

                $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

                if ($marketing_info) {
                    $data1['marketing_id'] = $marketing_info['marketing_id'];
                } else {
                    $data1['marketing_id'] = 0;
                }
            } else {
                $data1['affiliate_id'] = 0;
                $data1['commission'] = 0;
                $data1['marketing_id'] = 0;
                $data1['tracking'] = '';
            }

            $data1['language_id'] = $this->config->get('config_language_id');
            $data1['currency_id'] = $this->currency->getId($this->session->data['currency']);
            $data1['currency_code'] = $this->session->data['currency'];
            $data1['currency_value'] = $this->currency->getValue($this->session->data['currency']);

            $data1['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $data1['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $data1['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $data1['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $data1['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $data1['user_agent'] = '';
            }
            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $data1['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $data1['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($data1);

            $data['column_name'] = $this->language->get('column_name');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');

            $data['products'] = array();

            $this->load->model('tool/upload');

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {

                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }
                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }

                $data['products'][] = array(
                    'product_id'         => $product['product_id'],
                    'name'               => $product['name'],
                    'model'              => $product['model'],
                    'option'             => $option_data,
                    'quantity'           => $product['quantity'],
                    'subtract'           => $product['subtract'],
                    'recurring'          => $recurring,
                    'price'              => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
                    'total'              => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
                    'href'               => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'price_no_vat'       => $this->currency->format($product['price'], $this->session->data['currency']),
                    'price_no_vat_total' => $this->currency->format($product['price'] * $product['quantity'], $this->session->data['currency']),
                );
            }

            // Gift Voucher
            $data['vouchers'] = array();
            $data['totals'] = $this->cart->getTotals_azon();

            $data['payment'] = $this->load->controller('extension/payment/' . explode('.',$this->session->data['payment_method']['code'])[0]);
        } else {
            $data['redirect'] = $redirect;
        }

        $data['back'] = $this->url->link('checkout/checkout');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

        $this->hook->getHook('checkout/confirm/index/after', $data);
        $this->response->setOutput($this->load->view('checkout/confirm', $data));
    }

}
?>