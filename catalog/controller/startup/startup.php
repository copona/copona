<?php

class ControllerStartupStartup extends Controller {

    public function index() {

        // Store
        try {
            if ($this->request->server['HTTPS']) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '',
                            $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
            } else {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '',
                            $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
            }
        } catch (Exception $e) {
            $log = new Log('error.log');
            $private_message = "Error selecting correct table. DB connection problems or TABLE " . DB_PREFIX . "store not avvailable?";
            $log->write($private_message);
            $log->write($e->getMessage());
            error_log($private_message . " @" . __FILE__ . ":" . __LINE__, 0);
            die("Something went wrong, please, try again later...");
        }

        if (isset($this->request->get['store_id'])) {
            $this->config->set('config_store_id', (int)$this->request->get['store_id']);
        } else {
            if ($query->num_rows) {
                $this->config->set('config_store_id', $query->row['store_id']);
            } else {
                $this->config->set('config_store_id', 0);
            }
        }

        if (!$query->num_rows) {
            $this->config->set('config_url', HTTP_SERVER);
            $this->config->set('config_ssl', HTTPS_SERVER);
        }

        // Settings
        $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC";
        $cache_key = 'setting.all.' . md5($sql);
        $result_data = $this->cache->get($cache_key);
        if ($result_data === null) {
            $query = $this->db->query($sql);
            // $this->registry->get('log')->write('setting.all:' . dt(2));
            // pr();
            $result_data = [];
            if ($query->num_rows) {
                $result_data = $query->rows;
            }
            $this->cache->set($cache_key, $result_data);
        }

        // $query = $this->db->query($sql);

        foreach ($result_data as $result) {
            if (!$result['serialized']) {
                $this->config->set($result['key'], $result['value']);
            } else {
                $this->config->set($result['key'], json_decode($result['value'], true));
            }
        }

        // Url
        // Url
        $this->registry->set('url', new Url($this->config->get('config_url'), $this->config->get('config_ssl'), $this->registry));

        $this->config->set('theme_name', !empty($this->config->get('theme_default_directory')) ? $this->config->get('theme_default_directory') : 'default');
        $this->config->set('theme_uri', DIR_TEMPLATE . $this->config->get('theme_name'));

        //$this->config->set('theme_name', DIR_TEMPLATE . $this->config->get('theme_default_directory'));
        //$this->config->set('theme_uri', DIR_TEMPLATE . $this->config->get('theme_name'));

        // Language
        $code = '';

        /* Will detect language in the following priority:
         * 1. Read from URL
         * 2. Read from Cookie
         * 3. Read from PHP session
         * 4. If Forced language is set, then set from default language
         * 4a. if not, then try to detect from browser
         * 5. if nothing succeeds OR, detected language is not in available languages - we'll set default language
         *
        */

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();
        // $log = new Log('cookie.log');
        // $log->write($_COOKIE['language']);

        $default_language = $this->config->get('config_language');

        if (isset($this->request->get["_route_"])) {
            // 1. Read from URL
            $seo_path = explode('/', $this->request->get["_route_"]);
            if (array_key_exists($seo_path[0], $languages)) {
                $code = $seo_path[0];
                //remove first element! And shift!
                array_shift($seo_path);
                if (!empty($seo_path[0]) && $seo_path[0] == 'index.php') {
                    array_shift($seo_path);
                }
                if (empty($seo_path)) {
                    unset($this->request->get["_route_"]);
                } else {
                    $this->request->get["_route_"] = implode($seo_path, '/');
                }
            }
        } elseif (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'],
                $languages)) {
            // 2. Detect from Cookie
            $code = $this->request->cookie['language'];
        } elseif (isset($this->session->data['language']) && array_key_exists($this->session->data['language'],
                $languages)) {
            // 3. Detect from PHP session
            $code = $this->session->data['language'];
        } elseif ($this->config->get("config_forced_language")) {
            // 4. If set forced - set default language forced
            $code = $default_language;
        } else {
            // 5. Try to detect from the browser
            if (!empty($this->request->server['HTTP_ACCEPT_LANGUAGE']) && !array_key_exists($code, $languages)) {
                $detect = '';
                // lets use Default language, if it's accepted by Customer Browser correctly.
                // $browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
                $browser_languages = explode(",", $this->request->server['HTTP_ACCEPT_LANGUAGE']);
                for ($i = 0; $i < count($browser_languages); $i++) {
                    $browser_languages[$i] = substr($browser_languages[$i], 0, 2);
                }

                if (in_array($default_language, $browser_languages)) {
                    $detect = $default_language;
                }

                if (!$detect) {
                    // Try using local to detect the language
                    foreach ($browser_languages as $browser_language) {
                        foreach ($languages as $key => $value) {
                            if ($value['status']) {
                                $locale = explode(',', $value['locale']);
                                if (in_array($browser_language, $locale)) {
                                    $detect = $key;
                                    break 2;
                                }
                            }
                        }
                    }
                }
                $code = $detect ? $detect : '';
            }
        }

        // check, if language is available
        if (!array_key_exists($code, $languages)) {
            $code = $default_language;
        }

        $this->session->data['language'] = $code;

        if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
            setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
            $this->log->write($this->request->cookie['language']);
        }

        // Overwrite the default language object
        $language = new Language($code);
        $language->load($code);

        $this->registry->set('language', $language);

        // Set the config language_id
        $this->config->set('config_language_id', $languages[$code]['language_id']);

        // Default theme 'default' functions.php settings.
        if (file_exists(DIR_TEMPLATE . 'default/functions.php')) {
            require_once(DIR_TEMPLATE . 'default/functions.php');
        }

        // Execute Extensions Init, if them has a method.
        \Copona\System\Library\Extension\ExtensionManager::initAllCatalog();

        //Theme settings override
        if ($this->config->get('theme_name') != 'default' && file_exists($this->config->get('theme_uri') . '/functions.php')) {
            require_once($this->config->get('theme_uri') . '/functions.php');
        }


        $this->language->get('locale') ? setlocale(LC_ALL, $this->language->get('locale') . ".UTF-8") : '';
        // For numeric calculations, we need to have "dot" as decimal separator.
        // Numbers are still formatted by \Cart\Currency class.
        setlocale(LC_NUMERIC, "C");

        // Customer
        $customer = new Cart\Customer($this->registry);
        $this->registry->set('customer', $customer);

        // Customer Group
        if ($this->customer->isLogged()) {
            $this->config->set('config_customer_group_id', $this->customer->getGroupId());
        } elseif (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
            // For API calls
            $this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
            $this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
        }

        // Tracking Code
        if (isset($this->request->get['tracking'])) {
            setcookie('tracking', $this->request->get['tracking'], time() + 3600 * 24 * 1000, '/');

            $this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($this->request->get['tracking']) . "'");
        }


        // Affiliate
        $this->registry->set('affiliate', new Cart\Affiliate($this->registry));

        // Currency
        $code = '';

        $this->load->model('localisation/currency');

        $currencies = $this->model_localisation_currency->getCurrencies();

        if (isset($this->session->data['currency'])) {
            $code = $this->session->data['currency'];
        }

        if (isset($this->request->cookie['currency']) && !array_key_exists($code, $currencies)) {
            $code = $this->request->cookie['currency'];
        }

        if (!array_key_exists($code, $currencies)) {
            $code = $this->config->get('config_currency');
        }

        if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
            $this->session->data['currency'] = $code;
        }

        if (!isset($this->request->cookie['currency']) || $this->request->cookie['currency'] != $code) {
            setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
        }

        $this->registry->set('currency', new Cart\Currency($this->registry));

        // Tax
        $this->registry->set('tax', new Cart\Tax($this->registry));

        if (isset($this->session->data['shipping_address']) && isset($this->session->data['shipping_address']['country_id'])) {
            $this->tax->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
        } elseif ($this->config->get('config_tax_default') == 'shipping') {
            $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        if (
            isset($this->session->data['payment_address'])
            && isset($this->session->data['payment_address']['country_id'])
            && isset($this->session->data['payment_address']['zone_id'])
        ) {
            $this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
        } elseif ($this->config->get('config_tax_default') == 'payment') {
            $this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }


        $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

        // Weight
        $this->registry->set('weight', new Cart\Weight($this->registry));

        // Length
        $this->registry->set('length', new Cart\Length($this->registry));

        // Cart
        $this->registry->set('cart', new Cart\Cart($this->registry));
        $this->registry->get('cart')->init();


        // Encryption
        $this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));
    }
}
