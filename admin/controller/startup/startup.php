<?php
class ControllerStartupStartup extends Controller {

    public function index() {
        // Settings
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $this->config->set($setting['key'], $setting['value']);
            } else {
                $this->config->set($setting['key'], json_decode($setting['value'], true));
            }
        }

        // Language
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_admin_language')) . "'");

        if ($query->num_rows) {
            $this->config->set('config_language_id', $query->row['language_id']);
            $this->config->set('config_admin_language_locale', $query->row['locale']);
            $code = $query->row['code'];
            //prd();
        } else {
            $code = 'en'; // $this->config->get('config_admin_language');
        }

        // Language

        $language = new Language($code, $this->registry);
        $language->load($code);
        $this->registry->set('language', $language);

        // Customer
        $this->registry->set('customer', new Cart\Customer($this->registry));

        // Affiliate
        $this->registry->set('affiliate', new Cart\Affiliate($this->registry));

        // Currency
        $this->registry->set('currency', new Cart\Currency($this->registry));

        // Tax
        $this->registry->set('tax', new Cart\Tax($this->registry));

        if ($this->config->get('config_tax_default') == 'shipping') {
            $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        if ($this->config->get('config_tax_default') == 'payment') {
            $this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

        // Weight
        $this->registry->set('weight', new Cart\Weight($this->registry));

        // Length
        $this->registry->set('length', new Cart\Length($this->registry));

        // Cart
        $this->registry->set('cart', new Cart\Cart($this->registry));

        // Encryption
        $this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));


        $this->config->set('theme_name', !empty($this->config->get('theme_default_directory')) ? $this->config->get('theme_default_directory') : 'default');
        $this->config->set('theme_uri', DIR_CATALOG . "view/theme/" . $this->config->get('theme_name'));

        //Theme settings override
        if (file_exists($this->config->get('theme_uri') . '/functions.php')) {
            require_once($this->config->get('theme_uri') . '/functions.php');
        }
    }

}