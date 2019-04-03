<?php

class Language extends Controller
{
    private $default = 'en-gb';
    private $directory;
    private $code;
    private $data = [];
    private $db;
    private $config;
    private $languages;
    private $theme_language = [];

    public function __construct($code = 'en')
    {
        $registry = Registry::getInstance();

        if ($registry->has('db')) {
            $this->db = $registry->get('db');
        } else {
            $this->db = null;
        }

        /** @var Config config */
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

        // Theme translation override
        if ($this->config->get('theme_name')) {

            // TODO: this must be removed.
            $themes_language_file = DIR_TEMPLATE . $this->config->get('theme_name') . "/language/" . $this->directory . '.php';

            if (is_file($themes_language_file)) {
                require($themes_language_file);
                !empty($_) ? $this->theme_language = $_ : false;
            }

            // This must stay. Language files must have the same "structure" as in /catalog/language.
            $themes_language_file = DIR_TEMPLATE . $this->config->get('theme_name') . "/language/" . $this->directory . "/" . $this->directory . '.php';

            if (is_file($themes_language_file)) {
                require($themes_language_file);
            }
        }

        if (APPLICATION == 'admin') {
            // TODO: Load Themes Overriden Admin translations
        }

    }

    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
	
	public function has($key)
    {
        return isset($this->data[$key]);
    }

    // Please dont use the below function i'm thinking getting rid of it.
    public function all()
    {
        return $this->data;
    }

    // Please dont use the below function i'm thinking getting rid of it.
    public function merge(&$data)
    {
        array_merge($this->data, $data);
    }

    public function load($filename, &$data = [])
    {
        if ($filename == $this->code) {
            $filename = $this->languages[$this->code];
        }
        $_ = [];

        $extension_files = \Copona\System\Library\Extension\ExtensionManager::findLanguage($this->directory . "/" . $filename . ".php");
        if (!empty($extension_files)) {
            $file = $extension_files;
        } else {
            $file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';
        }

        if (is_file($file)) {
            require($file);
        } elseif (is_file(DIR_TEMPLATE . $filename . '/' . $this->directory . '.php')) {
            //Theme settings override
            require_once(DIR_TEMPLATE . $filename . '/' . $this->directory . '.php');
        } elseif (is_file(DIR_LANGUAGE . $this->directory . '/' . $this->directory . '.php')) {
            require(DIR_LANGUAGE . $this->directory . '/' . $this->directory . '.php');
        } elseif (is_file(DIR_LANGUAGE . $this->default . '/' . $filename . '.php')) {
            require(DIR_LANGUAGE . $this->default . '/' . $filename . '.php');
        } else {
            require(DIR_LANGUAGE . $this->default . '/' . $this->default . '.php');
        }

        // Themes language files overrride.
        if (is_file(DIR_TEMPLATE . $this->config->get('theme_name') . "/language/" . $this->directory . "/" . $filename . '.php')) {
            require(DIR_TEMPLATE . $this->config->get('theme_name') . "/language/" . $this->directory . "/" . $filename . '.php');
        }

        $this->data = array_merge($this->data, $_);
        // TODO: arrayis merged every time, to override same keys from theme settings
        // must be optimized.
        $this->data = array_merge($this->data, $this->theme_language);
        $data = array_merge($data, $this->data);

        return $this->data;
    }

}