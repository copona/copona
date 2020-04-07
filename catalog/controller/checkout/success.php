<?php

class ControllerCheckoutSuccess extends Controller {
    public function index() {
        $data = $this->load->language('checkout/success');

        $this->load->model('account/order');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('checkout/order');
        $this->load->model('extension/total/coupon');

        $data['config_azon_store_info_email'] = $this->config->get('config_azon_store_info_email');
        $data['config_telephone'] = $this->config->get('config_telephone');

        $data['after_purchase'] = false;


        //$debug = true;
        $debug = false;

        if ($this->request->get('debug')) {
            $debug = true;
        }

        $data['debug'] = $debug;


        // if($debug) {
        //     $this->session->data['order_id'] = 25081; //arnis@indeed.pro debug order id
        // }


        if (isset($this->session->data['order_id'])) {

            $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            // Ja ir kupons, tad to vajag atzīmēt, kā "izmantotu"
            // if($this->model_checkout_order->getOrderTotals($this->session->data['order_id'], 'coupon')){
            // ŠIS ir salabots! model/checkout/order metodē nomainīts property_exists uz method_exists !
            // izmainīts arī Copona! :)
            // $this->model_extension_total_coupon->confirm_azon($order);
            //}

            $products = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);


            $data['products'] = [];
            $data['currency_code'] = $order['currency_code'];
            $data['store_name'] = $this->config->get('config_name');
            $data['shipping_total'] = 0;
            $data['order_total'] = $this->currency->format($this->tax->calculate($order['total'], false, $this->config->get('config_tax')), $order['currency_code'], false, false);;

            // prd($data['order_total']);

            foreach ($this->model_checkout_order->getOrderTotals($this->session->data['order_id'], 'shipping') as $shipping) {
                $data['shipping_total'] += $shipping['value'];
            }

            $data['shipping_total'] = $this->currency->format($this->tax->calculate($data['shipping_total'], false, $this->config->get('config_tax')), $order['currency_code'], false, false);


            foreach ($products as $product) {
                $categories = $this->model_catalog_product->getCategories($product['product_id']);
                $product_categories = [];
                $category_name_one = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $category_info = $this->model_catalog_category->getCategory($category['category_id']);
                        $category_name_one = $category_info['name'];
                        $product_categories[] = [
                            'category_id' => $category_info['category_id'],
                            'name'        => $category_info['name'],
                        ];
                    }
                }


                $data['products'][] = [
                    'product_id'         => $product['product_id'],
                    'name'               => html_entity_decode($product['name']),
                    'sku'                => html_entity_decode($product['model']),
                    'model'              => html_entity_decode($product['model']),
                    'quantity'           => $product['quantity'],
                    'price'              => $this->currency->format($this->tax->calculate($product['price'], (!empty($product['tax_class_id']) ? $product['tax_class_id'] : 0), $this->config->get('config_tax')),
                        $order['currency_code']),
                    'price_numeric'      => $this->currency->format($this->tax->calculate($product['price'], (!empty($product['tax_class_id']) ? $product['tax_class_id'] : 0), $this->config->get('config_tax')), false,
                        false, false),
                    'total'              => $this->currency->format($this->tax->calculate($product['total'], (!empty($product['tax_class_id']) ? $product['tax_class_id'] : 0), $this->config->get('config_tax')), false,
                        false, false),
                    // trešai false - lai nav formatting
                    'product_categories' => $product_categories,
                    'category'           => $category_name_one,
                ];
            }
            // prd($data['products']);

            if (!$debug) {
                $this->cart->clear();
            }

            $data['after_purchase'] = true;

            // Add to activity log
            if ($this->config->get('config_customer_activity')) {
                $this->load->model('account/activity');

                if ($this->customer->isLogged()) {
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        'order_id'    => $this->session->data['order_id'],
                    ];

                    $this->model_account_activity->addActivity('order_account', $activity_data);
                } else {
                    $activity_data = [
                        'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
                        'order_id' => $this->session->data['order_id'],
                    ];

                    $this->model_account_activity->addActivity('order_guest', $activity_data);
                }
            }
            $data['order_id'] = $this->session->data['order_id'];
            //$data['order_id'] = 5;
            //$this->load->model('checkout/order');
            $data['free_product'] = false;
            $free_product_id = false;

            foreach ($this->model_account_order->getOrderProducts($data['order_id']) as $order_product) {
                if ($order_product['price'] == '0') {
                    $free_product_id = $order_product['product_id'];
                }
            }

            if ($free_product_id) {
                $free_product = $this->model_catalog_product->getProduct($free_product_id);
                if ($free_product) {
                    $data['free_product'] = $free_product;
                    $data['free_product']['image'] = 'https://' . $_SERVER['SERVER_NAME'] . '/image/' . $data['free_product']['image'];
                }
            }

            $data['payment_method'] = $this->session->data['payment_method'];
            $data['shipping_method'] = $this->session->data['shipping_method'];
            $data['shipping_address'] = $this->session->data['shipping_address'];

            $data['payment_instruction'] = nl2br( $this->cart->getPaymentInstruction() ) ;

            // prd($data['payment_instruction']);


            $data['order'] = $this->model_account_order->getOrder($data['order_id']);
            $shipping_method = explode('.', $data['shipping_method']['code'])[0];
            // $data['shipping_type_text'] = $data['text_' . $shipping_method];


            switch ($shipping_method) {
                case 'location_based_shipping':
                    $data['shipping_type_address'] = $data['shipping_address']['city']
                        . ", " . $data['shipping_address']['address_1']
                        . ", " . $data['shipping_address']['postcode']
                        . ", " . $data['shipping_address']['country']
                        . ".";
                    break;
                default:
                    $data['shipping_type_address'] = $data['shipping_method']['title']; // Omniva, Pasta stacija u.c.
            }


            $data['estimated_delivery_date'] = date("d-m-Y", time() + 24 * 3600 * 14); // Harkodēts!!! Nomainīt!!!!!!!!!!!!!!!
            $data['ordercheck_link'] = $this->url->link('account/ordercheck');

            // /*


            $data['text_thanks'] = sprintf($this->language->get('text_thanks'), $data['order_id']);
            $data['text_success'] = sprintf($this->language->get('text_success'), $data['order_id']);

            if (!$debug) {
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['guest']);
                unset($this->session->data['comment']);
                unset($this->session->data['order_id']);
                unset($this->session->data['coupon']);
                unset($this->session->data['reward']);
                unset($this->session->data['voucher']);
                unset($this->session->data['vouchers']);
                unset($this->session->data['totals']);

                // Beos MOD
                unset($this->session->data['order_comment']);
                unset($this->session->data['delivery_date']);
                unset($this->session->data['delivery_time']);
                unset($this->session->data['survey']);
                // unset($this->session->data['shipping_address']);
                unset($this->session->data['payment_address']);
            }
            // */
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf($data['text_customer'], $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true),
                $this->url->link('information/contact'));
        } else {
            $data['text_message'] = sprintf($data['text_guest'], $this->url->link('information/contact'));
        }

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        $this->response->setOutput($this->load->view('checkout/easy_success', $data));
    }
}