<?php

namespace Cart;

class Currency {
    public $currencies = [];
    private $code;
    private $log;

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->language = $registry->get('language');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        $this->code = $this->session->data('currency') ? $this->session->data('currency') : $this->config->get('config_currency');

        $this->log = new \Log('currency_format.log');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");

        foreach ($query->rows as $result) {
            $this->currencies[$result['code']] = array(
              'currency_id'   => $result['currency_id'],
              'title'         => $result['title'],
              'symbol_left'   => $result['symbol_left'],
              'symbol_right'  => $result['symbol_right'],
              'decimal_place' => $result['decimal_place'],
              'value'         => $result['value']
            );
        }
    }

    public function format2($number, $currency = '') {
        // Format with currency rate 1 without rounding!
        return $this->format($number, $currency, 1, true, false);
    }

    /*
     * Function, which just FORMATS the number, without any needless calculations.
     */

    public function format($number, $currency = '', $value = '', $format = true, $round = true) {

        if (!$currency) {
            $currency = $this->code;
        }

        if (empty($this->currencies[$currency])) {
            $keys = array_keys($this->currencies);
            $currency = $keys[0];
        }

        $symbol_left = html_entity_decode($this->currencies[$currency]['symbol_left']);
        $symbol_right = html_entity_decode($this->currencies[$currency]['symbol_right']);
        $decimal_place = $this->currencies[$currency]['decimal_place'];

        if (!$value) {
            $value = $this->currencies[$currency]['value'];
        }

        $amount = $value ? (float)$number * $value : (float)$number;
        $amount = round($amount, (int)$decimal_place);

        if (!$format) {
            return $amount;
        }

        $string = '';

        if ($symbol_left) {
            $string .= $symbol_left;
        }

        $string .= number_format($amount, (int)$decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        if ($symbol_right) {
            $string .= $symbol_right;
        }


        return $string;
    }

    public function convert($value, $from, $to) {
        if (isset($this->currencies[$from])) {
            $from = $this->currencies[$from]['value'];
        } else {
            $from = 1;
        }

        if (isset($this->currencies[$to])) {
            $to = $this->currencies[$to]['value'];
        } else {
            $to = 1;
        }

        return $value * ($to / $from);
    }

    public function getId($currency = '') {
        if (!$currency) {
            return $this->currencies[$this->code]['currency_id'];
        } elseif ($currency && isset($this->currencies[$currency])) {
            return $this->currencies[$currency]['currency_id'];
        } else {
            return 0;
        }
    }

    public function getSymbolLeft($currency = '') {
        if (!$currency) {
            return $this->currencies[$this->code]['symbol_left'];
        } elseif ($currency && isset($this->currencies[$currency])) {
            return $this->currencies[$currency]['symbol_left'];
        } else {
            return '';
        }
    }

    public function getSymbolRight($currency = '') {
        if (!$currency) {
            return $this->currencies[$this->code]['symbol_right'];
        } elseif ($currency && isset($this->currencies[$currency])) {
            return $this->currencies[$currency]['symbol_right'];
        } else {
            return '';
        }
    }

    public function getDecimalPlace($currency = '') {
        if (!$currency) {
            return $this->currencies[$this->code]['decimal_place'];
        } elseif ($currency && isset($this->currencies[$currency])) {
            return $this->currencies[$currency]['decimal_place'];
        } else {
            return 0;
        }
    }

    public function getCode() {
        return $this->code;
    }

    public function getValue($currency = '') {
        if (!$currency) {
            return $this->currencies[$this->code]['value'];
        } elseif ($currency && isset($this->currencies[$currency])) {
            return $this->currencies[$currency]['value'];
        } else {
            return 0;
        }
    }

    public function has($currency) {
        return isset($this->currencies[$currency]);
    }
}
