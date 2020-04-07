<?php

namespace Cart;

class Cart {

    public $cartProducts = [];
    private $data = [];
    private $dd_count = 0;
    private $cartTotal = 0;
    private $shipping = 0;
    private $shipping_methods = [];
    private $shipping_address = [];
    private $address2 = [];
    private $shipping_method = [];
    private $payment_method = [];
    private $payment_address = [];
    private $payment_methods = [];

    private $payment_instruction = ''; // Used in "success"  payment.

    public function __construct($registry) {

        $this->registry = &$registry;

        $this->config = $registry->get('config');
        $this->customer = $registry->get('customer');
        $this->session = $registry->get('session');
        $this->db = $registry->get('db');
        $this->tax = $registry->get('tax');
        $this->weight = $registry->get('weight');
        $this->hook = $registry->get('hook');
        $this->currency = $registry->get('currency');
        $this->cache = $registry->get('cache');

        // TODO: not needed?
        $this->language = $registry->get('language');
        $this->request = $registry->get('request');

        $this->load = $registry->get('load');
        $this->load->model('extension/extension');
        $this->load->model('catalog/content');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $this->load->model('localisation/country');
        $this->extension = $registry->get('model_extension_extension');

        $this->countries = $registry->get('model_localisation_country')->getCountries() ;



        $this->log = new \Log('cart.log');


        $this->cur_constr(); // Does the minimum currency constructor for formatting

        // in Admin - session can be empty!
        if (!empty($this->session->data['currency']) && !empty($this->currencies[$this->session->data['currency']])) {
            $this->decimal_places = $this->currencies[$this->session->data['currency']]['decimal_place'];
        } else {
            $this->decimal_places = 2;
        }

        if ($this->customer->getId()) {
            // We want to change the session ID on all the old items in the customers cart
            $this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

            // Once the customer is logged in we want to update the customers cart
            $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

            foreach ($cart_query->rows as $cart) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

                // The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
                $this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
            }
        }
        $this->cartProducts = $this->getProducts(true);


    }



    public function getProducts($update = false) {

        if (!$update) {
            return $this->cartProducts;
        }


        $product_data = [];
        $this->dd_count++;

        $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

        foreach ($cart_query->rows as $cart) {
            $stock = true;
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

            if ($product_query->num_rows && ($cart['quantity'] > 0)) {
                $option_price = 0;
                $option_points = 0;
                $option_weight = 0;

                $option_data = [];

                foreach (json_decode($cart['option']) as $product_option_id => $value) {
                    $option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, od.display, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                    if ($option_query->num_rows) {
                        if ($option_query->row['display'] != "") {
                            $option_query->row['name'] = $option_query->row['display'];
                        }
                        if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
                            $option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                            if ($option_value_query->num_rows) {
                                if ($option_value_query->row['price_prefix'] == '+') {
                                    $option_price += $option_value_query->row['price'];
                                } elseif ($option_value_query->row['price_prefix'] == '-') {
                                    $option_price -= $option_value_query->row['price'];
                                }

                                if ($option_value_query->row['points_prefix'] == '+') {
                                    $option_points += $option_value_query->row['points'];
                                } elseif ($option_value_query->row['points_prefix'] == '-') {
                                    $option_points -= $option_value_query->row['points'];
                                }

                                if ($option_value_query->row['weight_prefix'] == '+') {
                                    $option_weight += $option_value_query->row['weight'];
                                } elseif ($option_value_query->row['weight_prefix'] == '-') {
                                    $option_weight -= $option_value_query->row['weight'];
                                }

                                if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
                                    $stock = false;
                                }

                                $option_data[] = [
                                    'product_option_id'       => $product_option_id,
                                    'product_option_value_id' => $value,
                                    'option_id'               => $option_query->row['option_id'],
                                    'option_value_id'         => $option_value_query->row['option_value_id'],
                                    'name'                    => $option_query->row['name'],
                                    'value'                   => $option_value_query->row['name'],
                                    'type'                    => $option_query->row['type'],
                                    'quantity'                => $option_value_query->row['quantity'],
                                    'subtract'                => $option_value_query->row['subtract'],
                                    'price'                   => $option_value_query->row['price'],
                                    'price_prefix'            => $option_value_query->row['price_prefix'],
                                    'points'                  => $option_value_query->row['points'],
                                    'points_prefix'           => $option_value_query->row['points_prefix'],
                                    'weight'                  => $option_value_query->row['weight'],
                                    'weight_prefix'           => $option_value_query->row['weight_prefix'],
                                ];
                            }
                        } elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
                            foreach ($value as $product_option_value_id) {
                                $option_value_query = $this->db->query("SELECT pov.option_value_id"
                                    . ", pov.quantity, pov.subtract, pov.price, pov.price_prefix"
                                    . ", pov.points"
                                    . ", pov.points_prefix"
                                    . ", pov.weight"
                                    . ", pov.weight_prefix"
                                    . ", ovd.name FROM " . DB_PREFIX . "product_option_value pov "
                                    . "LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) "
                                    . "WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' "
                                    . "AND pov.product_option_id = '" . (int)$product_option_id . "' "
                                    . "AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                                if ($option_value_query->num_rows) {
                                    if ($option_value_query->row['price_prefix'] == '+') {
                                        $option_price += $option_value_query->row['price'];
                                    } elseif ($option_value_query->row['price_prefix'] == '-') {
                                        $option_price -= $option_value_query->row['price'];
                                    }

                                    if ($option_value_query->row['points_prefix'] == '+') {
                                        $option_points += $option_value_query->row['points'];
                                    } elseif ($option_value_query->row['points_prefix'] == '-') {
                                        $option_points -= $option_value_query->row['points'];
                                    }

                                    if ($option_value_query->row['weight_prefix'] == '+') {
                                        $option_weight += $option_value_query->row['weight'];
                                    } elseif ($option_value_query->row['weight_prefix'] == '-') {
                                        $option_weight -= $option_value_query->row['weight'];
                                    }

                                    if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
                                        $stock = false;
                                    }

                                    $option_data[] = [
                                        'product_option_id'       => $product_option_id,
                                        'product_option_value_id' => $product_option_value_id,
                                        'option_id'               => $option_query->row['option_id'],
                                        'option_value_id'         => $option_value_query->row['option_value_id'],
                                        'name'                    => $option_query->row['name'],
                                        'value'                   => $option_value_query->row['name'],
                                        'type'                    => $option_query->row['type'],
                                        'quantity'                => $option_value_query->row['quantity'],
                                        'subtract'                => $option_value_query->row['subtract'],
                                        'price'                   => $option_value_query->row['price'],
                                        'price_prefix'            => $option_value_query->row['price_prefix'],
                                        'points'                  => $option_value_query->row['points'],
                                        'points_prefix'           => $option_value_query->row['points_prefix'],
                                        'weight'                  => $option_value_query->row['weight'],
                                        'weight_prefix'           => $option_value_query->row['weight_prefix'],
                                    ];
                                }
                            }
                        } elseif ($option_query->row['type'] == 'text'
                            || $option_query->row['type'] == 'textarea'
                            || $option_query->row['type'] == 'file'
                            || $option_query->row['type'] == 'date'
                            || $option_query->row['type'] == 'datetime'
                            || $option_query->row['type'] == 'time') {
                            $option_data[] = [
                                'product_option_id'       => $product_option_id,
                                'product_option_value_id' => '',
                                'option_id'               => $option_query->row['option_id'],
                                'option_value_id'         => '',
                                'name'                    => $option_query->row['name'],
                                'value'                   => $value,
                                'type'                    => $option_query->row['type'],
                                'quantity'                => '',
                                'subtract'                => '',
                                'price'                   => '',
                                'price_prefix'            => '',
                                'points'                  => '',
                                'points_prefix'           => '',
                                'weight'                  => '',
                                'weight_prefix'           => '',
                            ];
                        }
                    }
                }

                $price = $product_query->row['price'];


                // Product Discounts
                $discount_quantity = 0;

                foreach ($cart_query->rows as $cart_2) {
                    if ($cart_2['product_id'] == $cart['product_id']) {
                        $discount_quantity += $cart_2['quantity'];
                    }
                }

                $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount 
                WHERE true 
                AND product_id = '" . (int)$cart['product_id'] . "' 
                AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
                AND quantity <= '" . (int)$discount_quantity . "' 
                AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) 
                ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

                if ($product_discount_query->num_rows) {
                    $price = $product_discount_query->row['price'];
                }

                // Product Specials
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special 
                WHERE true 
                AND product_id = '" . (int)$cart['product_id'] . "' 
                AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
                AND ((date_start IS NULL OR date_start < NOW()) AND (date_end IS NULL OR date_end > NOW())) 
                ORDER BY priority ASC, price ASC LIMIT 1");

                if ($product_special_query->num_rows) {
                    $price = $product_special_query->row['price'];
                }

                // Reward Points
                $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward 
                WHERE true 
                AND product_id = '" . (int)$cart['product_id'] . "' 
                AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

                if ($product_reward_query->num_rows) {
                    $reward = $product_reward_query->row['points'];
                } else {
                    $reward = 0;
                }


                // Downloads
                $download_data = [];

                $download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                foreach ($download_query->rows as $download) {
                    $download_data[] = [
                        'download_id' => $download['download_id'],
                        'name'        => $download['name'],
                        'filename'    => $download['filename'],
                        'mask'        => $download['mask'],
                    ];
                }

                // Stock
                if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
                    $stock = false;
                }

                $recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

                if ($recurring_query->num_rows) {
                    $recurring = [
                        'recurring_id'    => $cart['recurring_id'],
                        'name'            => $recurring_query->row['name'],
                        'frequency'       => $recurring_query->row['frequency'],
                        'price'           => $recurring_query->row['price'],
                        'cycle'           => $recurring_query->row['cycle'],
                        'duration'        => $recurring_query->row['duration'],
                        'trial'           => $recurring_query->row['trial_status'],
                        'trial_frequency' => $recurring_query->row['trial_frequency'],
                        'trial_price'     => $recurring_query->row['trial_price'],
                        'trial_cycle'     => $recurring_query->row['trial_cycle'],
                        'trial_duration'  => $recurring_query->row['trial_duration'],
                    ];
                } else {
                    $recurring = false;
                }


                // TODO: CUSTOM MOD: for EAN value - NOT FOR PRODUCTION!
                // also in catalog/model/product/product.php
                if ((double)$product_query->row['ean']) {
                    $ean = $this->tax->calculate($product_query->row['ean'],
                        $this->config->get('flat_per_product_tax_class_id'), $this->config->get('config_tax'));
                } else {
                    $ean = $product_query->row['ean'];
                }


                // TODO: check in common/cart.php controller!
                // We need product price + option price for opened product.
                $price_enduser = $this->currency->format($this->tax->calculate($price, $product_query->row['tax_class_id'], $this->config->get('config_tax')), '', '', false)
                    + $this->currency->format($this->tax->calculate($option_price, $product_query->row['tax_class_id'], $this->config->get('config_tax')), '', '',
                        false);
                $price_enduser_total = $price_enduser * $cart['quantity'];

                $price_enduser_formatted = $this->currency->format2($price_enduser);
                $price_enduser_total_formatted = $this->currency->format2($price_enduser_total);

                $product_taxes = $this->tax->getRates($price_enduser_total, $product_query->row['tax_class_id']);
                $tax_amount = 0;
                foreach ($product_taxes as $tax) {
                    $tax_amount += ($price_enduser_total * $tax['rate'] * 0.01) / (1 + $tax['rate'] * 0.01);
                }

                $price = ($price + $option_price);
                $price_total = $price * $cart['quantity'];
                $tax = $price_enduser_total - $price_total;

                if ($product_query->row['image']) {
                    $thumb = $this->registry->get('model_tool_image')->{$this->config->get('theme_default_image_cart_resize', 'resize')}($product_query->row['image'],
                        $this->config->get($this->config->get('config_theme') . '_image_cart_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
                } else {
                    $thumb = $this->registry->get('model_tool_image')->{$this->config->get('theme_default_image_cart_resize', 'resize')}($this->config->get('config_no_image',
                        'placeholder.png'), $this->config->get($this->config->get('config_theme') . '_image_cart_width'),
                        $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
                }

                $href = $this->registry->get('url')->link('product/product', 'product_id=' . $product_query->row['product_id']);


                $attribute_groups = $this->registry->get('model_catalog_product')->getProductAttributes($product_query->row['product_id']);

                $product_data[] = [
                    'cart_id'                       => $cart['cart_id'],
                    'product_id'                    => $product_query->row['product_id'],
                    'name'                          => $product_query->row['name'],
                    'model'                         => $product_query->row['model'],
                    'shipping'                      => $product_query->row['shipping'],
                    'image'                         => $product_query->row['image'],
                    'thumb'                         => $thumb,
                    'href'                          => $href,
                    'option'                        => $option_data,
                    'ean'                           => $ean,
                    'download'                      => $download_data,
                    'quantity'                      => $cart['quantity'],
                    'minimum'                       => $product_query->row['minimum'],
                    'subtract'                      => $product_query->row['subtract'],
                    'stock'                         => $stock,
                    'price'                         => $price,
                    'total'                         => $price_total,
                    'content_meta'                  => $this->registry->get('model_catalog_content')->getContentMeta($product_query->row['product_id'], 'product'),
                    'tax'                           => $tax,
                    'tax_amount'                    => $tax_amount / $cart['quantity'], // Do not ROUND and FORMAT!
                    'tax_amount_total'              => $tax_amount,
                    'price_without_tax'             => ($price_enduser_total - $tax_amount) / $cart['quantity'],
                    'total_without_tax'             => $price_enduser_total - $tax_amount,
                    'price_enduser'                 => $price_enduser,
                    'price_enduser_formatted'       => $price_enduser_formatted,
                    'price_enduser_total'           => $price_enduser_total,
                    'price_enduser_total_formatted' => $price_enduser_total_formatted,
                    'reward'                        => $reward * $cart['quantity'],
                    'points'                        => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
                    'tax_class_id'                  => $product_query->row['tax_class_id'],
                    'weight'                        => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
                    'weight_class_id'               => $product_query->row['weight_class_id'],
                    'length'                        => $product_query->row['length'],
                    'width'                         => $product_query->row['width'],
                    'height'                        => $product_query->row['height'],
                    'length_class_id'               => $product_query->row['length_class_id'],
                    'recurring'                     => $recurring,
                    'attribute_groups'              => $attribute_groups,
                ];

            } else {
                $this->remove($cart['cart_id']);
            }
        }

        $this->hook->getHook('system/library/cart/cart/getProducts/beforeafter', $product_data);

        return $product_data;
    }

    public function add($product_id, $quantity = 1, $option = [], $recurring_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

        if (!$query->row['total']) {

            $sql = "INSERT " . DB_PREFIX . "cart SET api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', 
customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', 
product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', 
`option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()";
            $this->db->query($sql);
        } else {
            $sql = "UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (int)$quantity . ") WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'";
            $this->db->query($sql);
        }
        $this->cartProducts = $this->getProducts(true);
    }

    public function update($cart_id, $quantity) {
        $this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        $this->cartProducts = $this->getProducts(true);

        $this->setShippingMethods();



        // TODO: Cart is very dependant from shipping methods

        $this->setShippingMethod( $this->getShippingMethod( $this->getShippingMethodCode() ) );

    }

    public function remove($cart_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        $this->cartProducts = $this->getProducts(true);
    }

    public function clear() {
        $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        $this->cartProducts = $this->getProducts(true);
    }

    public function getRecurringProducts() {
        $product_data = [];

        foreach ($this->cartProducts as $value) {
            if ($value['recurring']) {
                $product_data[] = $value;
            }
        }

        return $product_data;
    }

    public function getWeight() {
        $weight = 0;

        foreach ($this->cartProducts as $product) {
            if ($product['shipping']) {
                $weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
            }
        }

        return $weight;
    }







    public function init($update = false) {


        if (!is_array($this->session->data('shipping_address'))) {
            $this->session->data['shipping_address'] = [];
        }

        $this->setShippingAddress($this->session->data('shipping_address'));

        // prd($this->session->data('payment_address'));

        $this->setPaymentAddress($this->session->data('payment_address'));

        $this->setShippingMethods();
        $this->setPaymentMethods();

        $this->setShippingMethod($this->session->data('shipping_method'));
        $this->setPaymentMethod($this->session->data('payment_method'));

        $this->setAddress2($this->session->data('address2'));


        $this->setPaymentInstruction($this->session->data('payment_instruction'));


        //azon metode init();

        /*
        if ($this->customer->getId()) {
            // We want to change the session ID on all the old items in the customers cart
            $this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");
            // Once the customer is logged in we want to update the customers cart
            $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
            foreach ($cart_query->rows as $cart) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");
                // The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
                $this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
            }
        }  */
        // $this->cartProducts = $this->getProducts(true);
    }


    public function getSubTotal() {
        $total = 0;
        foreach ($this->cartProducts as $product) {
            //TODO: dirty workaround for free_product gift - bezmaksas produkts.
            if (empty($product['price_enduser_total'])) {
                $product['price_enduser_total'] = 0;
            }

            $total += $product['price_enduser_total'];
        }


        if ($this->shipping_method) {
            $total += $this->shipping_method['price_enduser'];
        }

        foreach ($this->getTaxes() as $tax) {
            $total -= $tax;
        }

        //  Convert back to BASE currency
        $total = $this->currency->convert($total, $this->session->data['currency'], 1);

        return $total;
    }

    public function getTaxes() {
        $tax = [];
        $enduser_prices = [];
        foreach ($this->cartProducts as $product) {
            !isset($enduser_prices[$product['tax_class_id']]) ? $enduser_prices[$product['tax_class_id']] = 0 : false;
            $enduser_prices[$product['tax_class_id']] += isset($product['price_enduser_total']) ? $product['price_enduser_total'] : 0;
        }

        $tax_classes = $this->tax->getTaxClasses();

        foreach ($enduser_prices as $tax_class_id => $enduser_price) {
            if (isset($tax_classes[$tax_class_id])) {
                $multiplier = 1;
                foreach ($tax_classes[$tax_class_id] as $tax_rate_id => $tax_rate) {
                    $multiplier *= (1 + $tax_rate['rate'] / 100);
                }

                $total_wo_tax = $enduser_price / $multiplier;
                // now - extract tax from every "total" without tax

                foreach ($tax_classes[$tax_class_id] as $tax_rate_id => $tax_rate) {
                    if (!isset($tax[$tax_rate_id])) {
                        $tax[$tax_rate_id] = 0;
                    }
                    $tax[$tax_rate_id] += $total_wo_tax * ($tax_rate['rate'] / 100);
                }
            }
        }


        if ($this->shipping_method) {

            $tax_class_id = $this->shipping_method['tax_class_id'];
            $enduser_price = $this->shipping_method['price_enduser'];

            if (isset($tax_classes[$tax_class_id])) {
                $multiplier = 1;
                foreach ($tax_classes[$tax_class_id] as $tax_rate_id => $tax_rate) {
                    $multiplier *= (1 + $tax_rate['rate'] / 100);
                }

                $total_wo_tax = $enduser_price / $multiplier;
                // now - extract tax from every "total" without tax

                foreach ($tax_classes[$tax_class_id] as $tax_rate_id => $tax_rate) {
                    if (!isset($tax[$tax_rate_id])) {
                        $tax[$tax_rate_id] = 0;
                    }
                    $tax[$tax_rate_id] += $total_wo_tax * ($tax_rate['rate'] / 100);
                }
            }

        }


        // ROund every total value.
        array_walk($tax, function (&$val) {
            $val = round($val, $this->decimal_places);
        });
        return $tax;
    }


    public function getTotal( $formatted = false ) {
        $total = 0;
        foreach ($this->cartProducts as $product) {
            // $total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
            $total += $product['price_enduser_total'];
        }
        return $formatted ? $this->currency->format($total) : $total ;
    }

    public function getShipping() {
        $this->getTotals_azon();
        return $this->shipping;
    }



    public function countProducts($product_id = false) {
        $product_total = 0;

        $products = $this->cartProducts;

        foreach ($products as $product) {
            if ($product_id) {
                if ($product['product_id'] == $product_id) {
                    $product_total += $product['quantity'];
                }
            } else {
                $product_total += $product['quantity'];
            }

        }

        return $product_total;
    }

    public function hasProducts() {
        return count($this->cartProducts);
    }

    public function hasRecurringProducts() {
        return count($this->getRecurringProducts());
    }






    public function hasStock() {
        foreach ($this->cartProducts as $product) {
            if (!$product['stock']) {
                return false;
            }
        }

        return true;
    }

    public function hasShipping() {
        foreach ($this->cartProducts as $product) {
            if ($product['shipping']) {
                return true;
            }
        }

        return false;
    }

    public function hasDownload() {
        foreach ($this->cartProducts as $product) {
            if ($product['download']) {
                return true;
            }
        }

        return false;
    }


    /*
    TODO: This is needed to generate Currencies array
    to format data according to this.
    */
    private function cur_constr() {

        $sql = "SELECT * FROM " . DB_PREFIX . "currency where true ";

        $cache_key = 'currency.' . md5($sql);
        $result_data = $this->cache->get($cache_key);

        // pr($result_data);

        if ($result_data === null) {
            $query = $this->db->query($sql);
            $result_data = [];
            if ($query->num_rows) {
                $result_data = $query->rows;
            }
            $this->cache->set($cache_key, $result_data);
        }


        // $query = $this->db->query($sql);

        foreach ($result_data as $result) {
            $this->currencies[$result['code']] = [
                'currency_id'   => $result['currency_id'],
                'title'         => $result['title'],
                'symbol_left'   => $result['symbol_left'],
                'symbol_right'  => $result['symbol_right'],
                'decimal_place' => $result['decimal_place'],
                'value'         => $result['value'],
            ];
        }

        //        if (isset($this->request->get['currency']) && (array_key_exists($this->request->get['currency'], $this->currencies))) {
        //            $code = $this->request->get['currency'];
        //        } elseif ((isset($this->session->data['currency'])) && (array_key_exists($this->session->data['currency'], $this->currencies))) {
        //            $code = $this->session->data['currency'];
        //        } elseif ((isset($this->request->cookie['currency'])) && (array_key_exists($this->request->cookie['currency'], $this->currencies))) {
        //            $code = $this->request->cookie['currency'];
        //        } else {
        //            $code = $this->config->get('config_currency');
        //        }

    }








    /* collect shipping methods */

    public function getAddress2() {
        return $this->address2;
    }

    /**
     * @return void
     */

    public function clearAddress2( ) {

        $this->session->data['address2'] = $this->address2 = [] ;
    }

    /**
     * @return mixed
     */
    public function setAddress2($address2) {

        //  $this->log->write ( $address2 ) ;


        $fields = [
            'firstname',
            'email',
            'telephone',
            'address_1',
            'city',
            'postcode',
            'country_id',
            'billing_address_details',
        ];


        foreach ($fields as $key) {
            // $data[$field] = !empty($address2[$field]) ? $address2[$field] : '';

            if(isset($address2[$key])) {
                $this->address2[$key] = $address2[$key];
            } elseif(isset($this->address[$key])) {
                //continue;
            } else {
                $this->address2[$key] = '';
            }

        }

        $this->session->data['address2'] = $this->address2;
        return true;
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function getShippingMethod($code = '') {

        // pr(ddd());

        $method = [];

        if ($code) {

            $shipping = explode('.', $code);

            if (isset($this->shipping_methods[$shipping[0]]['quote'][$shipping[1]])) {
                $method = $this->shipping_methods[$shipping[0]]['quote'][$shipping[1]];
            }

        } elseif ($this->shipping_method) {
            $method = $this->shipping_method;
        } else {


        }

        // prd($key);
        return $method;
    }

    /**
     * @return mixed
     */
    public function setShippingMethod($method = []) {

        //  $this->log->write ( ddd() ) ;

        // pr($method);
        //  pr(ddd());
        // pr($this->shipping_methods);

        // $this->log->write( $method);

        if ($method && isset($method['cost']) && $this->hasProducts()) {

            $price_enduser = $this->currency->format($this->tax->calculate($method['cost'], $method['tax_class_id'], $this->config->get('config_tax')), '', '', false);
            $price_enduser_formatted = $this->currency->format2($price_enduser);
            // $price_enduser_total_formatted = $this->currency->format2($price_enduser_total);

            $method['price_enduser'] = $price_enduser;
            $method['price_enduser_formatted'] = $price_enduser_formatted;

        } elseif ($this->hasProducts() && $this->shipping_methods) {

            /* Settings FIRST shipping method, as current! */

            $key1 = key($this->shipping_methods);
            $key2 = key($this->shipping_methods[$key1]['quote']);
            $method = $this->shipping_methods[$key1]['quote'][$key2];


            $price_enduser = $this->currency->format($this->tax->calculate($method['cost'], $method['tax_class_id'], $this->config->get('config_tax')), '', '', false);
            $price_enduser_formatted = $this->currency->format2($price_enduser);
            // $price_enduser_total_formatted = $this->currency->format2($price_enduser_total);
            $method['price_enduser'] = $price_enduser;
            $method['price_enduser_formatted'] = $price_enduser_formatted;


        } else {
            $method = [];
        }

        // prd($method);

        $this->session->data['shipping_method'] = $this->shipping_method = $method;


    }

    /**
     * @return mixed
     */
    public function getShippingMethodCode() {
        return isset($this->shipping_method['code']) ? $this->shipping_method['code'] : '';
    }

    /**
     * @return mixed
     */
    public function getShippingMethods() {
        return $this->shipping_methods;
    }

    public function getTotals($par = []) {
        return $this->getTotals_azon($par);
    }

    public function getTotals_azon($params = []) {
        $data = [];
        $total = 0;
        $taxes = $this->getTaxes();

        $totals = [];
        $total_data = [
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total,
        ];

        $this->cartTotal = 0;
        $this->shipping = 0;

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = [];
            $results = $this->extension->getExtensions('total');
            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }
            array_multisort($sort_order, SORT_ASC, $results);
            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/total/' . $result['code']);
                    $this->registry->get('model_extension_total_' . $result['code'])->getTotal($total_data);
                }
            }

            foreach ($total_data['totals'] as $total) {

                // Needed, for correct TOTAL price for cart
                // because of ROUNDING
                if ($total['code'] != 'total') {
                    $this->cartTotal += $this->currency->format($total['value'], $this->session->data['currency'], '', false);
                }

                // TODO: commented!?
                // $this->shipping += $total['code'] == 'shipping' ? $total['value'] : 0;

                $data['totals'][] = [
                    'code'       => $total['code'],
                    'title'      => $total['title'],

                    // We need to covert between currencies, to set correct number
                    'value'      => $total['code'] == 'total' ? $this->currency->convert($this->cartTotal, $this->session->data['currency'], 1) : $total['value'],
                    'sort_order' => $total['sort_order'],
                    'text'       => $total['code'] == 'total' ? $this->currency->format2($this->cartTotal) : $this->currency->format($total['value'],
                        $this->session->data['currency']),
                    // 'price_enduser_formatted'       => $this->format2,
                ];

            }
        }
        return $data['totals'];
    }

    public function setShippingMethods() {

        // pr($this->shipping_address['country_id']);

        if ($this->shipping_address) {

            $method_data = [];
            $results = $this->extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/shipping/' . $result['code']);


                    $quote = $this->registry->get('model_extension_shipping_' . $result['code'])->getQuote($this->shipping_address);

                    //                    pr($result['code']);
                    //                    pr($quote);
                    //                    pr($this->shipping_address);

                    //  $this->log->write (   $quote['quote']  ) ;

                    if ($quote) {

                        $method_data[$result['code']] = [
                            'title'      => $quote['title'],
                            'template'   => !empty($quote['template']) ? $quote['template'] : '',
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error'],
                            'sub_quote'  => (!empty($quote['sub_quote']) && $quote['sub_quote'] ? $quote['sub_quote'] : ''),
                        ];
                    }
                }
            }


            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            //pr($method_data);


            $this->shipping_methods = $method_data;
        }

    }

    /**
     * @return mixed
     */
    public function getPaymentAddress() {
        return $this->payment_address;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod() {
        return $this->payment_method;
    }

    /**
     * @return mixed
     */
    public function setPaymentMethod($method = []) {
        // TODO: this comes without validation
        // we are not validating anything here !
        // probaly, should be validated for available methods only

        if ($method) {
            //$price_enduser = $this->currency->format($this->tax->calculate($method['cost'], $method['tax_class_id'], $this->config->get('config_tax')), '', '', false);
            //$price_enduser_formatted = $this->currency->format2($price_enduser);

            //$method['price_enduser'] = $price_enduser;
            //$method['price_enduser_formatted'] = $price_enduser_formatted;

            // We can't validate anything here. Just read variables from session.
            if (is_string($method) && isset($this->payment_methods[$method])) {
                $method = $this->payment_methods[$method];
            } elseif (is_array($method) && isset($method['code'])) {
                $method = $method;
            } else {
                $method = [];
            }


        }

        //        pr($this->payment_methods);

        $this->session->data['payment_method'] = $this->payment_method = $method;
    }


    public function setPaymentInstruction($text) {
        $this->session->data['payment_instruction'] = $this->payment_instruction = $text;
    }

    public function getPaymentInstruction() {
        return $this->payment_instruction;
    }


    /**
     * Correct Total with discount
     * @return int
     */
    public function getCartTotal($format = false) {
        // Thus function generates cart total! Together with correct totals built.
        $this->getTotals_azon();

        if ($format) {
            return $this->currency->format($this->cartTotal, '', 1, true);
        } else {
            return $this->currency->format($this->cartTotal, '', 1, false);
        }
    }

    /**
     * @return mixed
     */
    public function getPaymentMethods() {
        return $this->payment_methods;
    }

    public function setPaymentMethods() {



        $method_data = [];
        $results = $this->extension->getExtensions('payment');


        // pr($results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('extension/payment/' . $result['code']);

                // TODO: total can be requested in any Total module directly, without passing as argument.
                // But that must be included in all modules then...

                $method = $this->registry->get('model_extension_payment_' . $result['code'])->getMethod($this->payment_address, $this->getTotal());

                if ($method) {
                    $method_data[$result['code']] = [
                        'title'      => $method['title'],
                        'code'       => $method['code'],
                        'template'   => !empty($method['template']) ? $method['template'] : '',
                        // 'quote'      => $method['quote'],
                        'sort_order' => $method['sort_order'],
                        // 'error'      => $method['error'],
                    ];
                }
            }

            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);
            // prd($method_data);
            $this->payment_methods = $this->session->data['payment_methods'] = $method_data;


        }
    }



    public function setShippingAddress($address = []) {
        // $this->shipping_address = $this->session->data('shipping_address');



        if (!is_array($address)) {
            $address = [];
        }


        if (empty($address['country_id'])) {
            $address['country_id'] = $this->config->get('config_country_id');
        }

        $address['zone_id'] = isset($address['zone_id']) ? $address['zone_id'] : 0;

        $fields = [
            'firstname',
            'telephone',
            'email',
            'lastname',
            'company',
            'company_id',
            'tax_id',
            'address_1',
            'address_2',
            'city',
            'postcode',
            'zone',
            'zone_id',
            'country',
            'country_id',
            'address_format',
            'custom_field', // array
            'customer_group_id', // array
        ];

        foreach ($fields as $field) {
            if (isset($address[$field]) && !is_array( $address[$field] )) {
                $this->shipping_address[$field] = $address[$field];
            } elseif ( isset($address[$field]) && is_array( $address[$field] ) ) {
                $this->log->write("ERROR: $field is ARRAY! No WAY! Not allowed!");
                $this->shipping_address[$field] = '';
            } elseif (isset($this->shipping_address[$field])) {
                // continue
            } else {
                $this->shipping_address[$field] = '';
            }
        }

        if($this->shipping_address["country_id"]) {
            $this->shipping_address["country"] = $this->countries[ $this->shipping_address["country_id"]]['name'] ;
        }

        $this->session->data['shipping_address'] = $this->shipping_address;

        $this->tax->setShippingAddress($this->shipping_address['country_id'], $this->shipping_address['zone_id']);

    }

    public function getShippingAddress() {
        return $this->shipping_address;
    }


    public function setPaymentAddress($address = []) {

        if (!$address) {
            $address = $this->shipping_address;
        }

        if (empty($address['country_id'])) {
            $address['country_id'] = $this->config->get('config_country_id');
        }

        $address['zone_id'] = isset($address['zone_id']) ? $address['zone_id'] : 0;


        $fields = [
            'firstname',
            'lastname',
            'company',
            'company_id',
            'tax_id',
            'address_1',
            'address_2',
            'city',
            'postcode',
            'zone',
            'zone_id',
            'country',
            'country_id',
            'address_format',
            'custom_field', // array
            'email', // 2 new fields
            'telephone', // 2 new fields
        ];


        foreach ($fields as $field) {
            if (isset($address[$field])) {
                $this->payment_address[$field] = $address[$field];
            } elseif (isset($this->payment_address[$field])) {
                // continue
            } else {
                $this->payment_address[$field] = '';
            }
        }

        if($this->payment_address["country_id"]) {
            $this->payment_address["country"] = $this->countries[ $this->payment_address["country_id"]]['name'] ;
        }


        $this->session->data['payment_address'] = $this->payment_address;
    }
    // TODO, is this done?
    public function setComment($comment) {
        $this->session->data['comment'] = $this->comment = $comment;
    }


    public function unset() {

        unset($this->session->data['shipping_country_id']);
        unset($this->session->data['shipping_zone_id']);
        unset($this->session->data['shipping_postcode']);
        unset($this->session->data['payment_country_id']);
        unset($this->session->data['payment_zone_id']);
    }

}
