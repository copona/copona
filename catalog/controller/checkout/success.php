<?php

class ControllerCheckoutSuccess extends Controller
{
    public function index()
    {
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

        // Process, Notify IF and ONLY, current Order Status ID is NOT 0 ! That means,
        // it's already somewhere (in Payment modules)  processed.

        $order = isset($this->session->data['order_id']) ? $this->model_checkout_order->getOrder($this->session->data['order_id']) : [];

        if ($order && $order['order_status_id']) {

            // If Coupon is selected, then it will be set as "used" here
            // if($this->model_checkout_order->getOrderTotals($this->session->data['order_id'], 'coupon')){
            // property_exists changed to method_exists !
            // $this->model_extension_total_coupon->confirm_azon($order);
            //}

            $products = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);

            $data['products'] = [];
            $data['currency_code'] = $order['currency_code'];
            $data['store_name'] = $this->config->get('config_name');
            $data['shipping_total'] = 0;
            $data['order_total'] = $this->currency->format($this->tax->calculate($order['total'], false, $this->config->get('config_tax')), $order['currency_code'], false, false);;

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

                if (!empty($product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 150, 150);
                }

                if (!$image) {
                    $image = $this->model_tool_image->resize(Config::get('config_no_image', 'placeholder.png'), 150, 150);
                }

                $data['products'][] = [
                    'product_id'         => $product['product_id'],
                    'thumb'              => $image,
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

            $data['payment_instruction'] = nl2br($this->cart->getPaymentInstruction());

            $shipping_method = $this->cart->getShippingMethod();
            $data['shipping_type_address'] = '';

            if ($shipping_method) {
                switch (explode(".", $shipping_method['code'])[0]) {
                    case 'location_based_shipping':
                        $data['shipping_type_address'] = $data['shipping_address']['city']
                            . ", " . $data['shipping_address']['address_1']
                            . ", " . $data['shipping_address']['postcode']
                            . ", " . $data['shipping_address']['country']
                            . ".";
                        break;
                    default:
                        $data['shipping_type_address'] = $shipping_method['title']; // Omniva, Pasta stacija u.c.
                }
            }

            $data['estimated_delivery_date'] = date("d-m-Y", time() + 24 * 3600 * 14); // Harkodēts!!! Nomainīt!!!!!!!!!!!!!!!
            $data['ordercheck_link'] = $this->url->link('account/ordercheck');

            $data['text_thanks'] = sprintf($this->language->get('text_thanks'), $data['order_id']);
            $data['text_success'] = sprintf($this->language->get('text_success'), $data['order_id']);


            $this->document->setTitle($this->language->get('heading_title'));

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
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

            // Clear Everything uppon successfull order !
            // Clearing All and Everything in Cart !
            if (!$debug) {
                $this->cart->unset();
            }
            $this->response->setOutput($this->load->view('checkout/easy_success', $data));

        } else {


            $this->load->language('error/not_found');
            $this->document->setTitle($this->language->get('heading_title'));
            $data['breadcrumbs'] = [];
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            if (isset($this->request->get['route'])) {
                $url_data = $this->request->get;
                unset($url_data['_route_']);
                $route = $url_data['route'];
                unset($url_data['route']);
                $url = '';
                if ($url_data) {
                    $url = '&' . urldecode(http_build_query($url_data, '', '&'));
                }

                $data['breadcrumbs'][] = [
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link($route, $url, $this->request->server['HTTPS']),
                ];
            }

            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_error'] = $this->language->get('text_error');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['continue'] = $this->url->link('common/home');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            // $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $this->response->setOutput($this->load->view('error/not_found', $data));

        }
    }
}
