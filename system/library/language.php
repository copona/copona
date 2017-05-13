<?php
class Language extends Controller {
    private $default = 'en-gb';
    private $directory, $code;
    private $data = array();
    private $db;
    private $config;
    private $languages;

    public function __construct($code = 'en', $registry) {

        $this->db = $registry->get('db');
        $this->config = $registry->get('config');

        if ($this->db) {
            $languages = $this->db->query("select * from `" . DB_PREFIX . "language` where `code` = '" . $code . "'");
        }
        if ($this->db && $languages->num_rows) {
            foreach ($languages->rows as $val) {
                $this->languages[$val['code']] = $val['directory'];
            }
            $this->code = $code;
        } else {
            // Default language English gb eb-gb
            $this->languages['en'] = 'en-gb';
            $this->code = 'en';
        }

        $this->directory = $this->languages[$this->code];
        !$this->directory ? !pr($this->languages) && !pr($this->code) : false;
    }

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    // Please dont use the below function i'm thinking getting rid of it.
    public function all() {
        return $this->data;
    }

    // Please dont use the below function i'm thinking getting rid of it.
    public function merge(&$data) {
        array_merge($this->data, $data);
    }

    public function load($filename, &$data = array()) {
        if ($filename == $this->code) {
            $filename = $this->languages[$this->code];
        }
        $_ = array();

        $file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';
        if (is_file($file)) {
            require($file);
        } elseif (is_file(DIR_TEMPLATE . $filename . '/' . $this->directory . '.php')) {
            //Theme settings override
            require_once(DIR_TEMPLATE . $filename . '/' . $this->directory . '.php');
        } elseif (is_file(DIR_LANGUAGE . $this->directory . '/' . $this->directory . '.php')) {
            require( DIR_LANGUAGE . $this->directory . '/' . $this->directory . '.php' );
        } elseif (is_file(DIR_LANGUAGE . $this->default . '/' . $filename . '.php')) {
            require(DIR_LANGUAGE . $this->default . '/' . $filename . '.php' );
        } else {
            //pr($filename);
            require(DIR_LANGUAGE . $this->default . '/' . $this->default . '.php' );
        }

        $this->data = array_merge($this->data, $_);

        $data = array_merge($data, $this->data);

        return $this->data;
    }

}