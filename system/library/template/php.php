<?php

namespace Template;

final class PHP {
    private $data = array();

    public function __construct($registry) {
        $this->config = $registry->get('config');
        //$this->db = $registry->get('db');
        //$this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->request = $registry->get('request');
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    public function render($template) {
        $extensions_dir = preg_replace('/\/[a-z]*\/$/','',DIR_SYSTEM);

        // pr($template);
        // TODO: optimize this!
        defined('DIR_CATALOG')
            ? $extension_files = glob($extensions_dir . "/extensions/*/*/admin/view/template/" . $template)
            : $extension_files = glob($extensions_dir . "/extensions/*/*/catalog/view/theme/default/template/" . $template . ".php");
        // pr($extension_files);

        // First, let's check for Extension template
        if(!empty($extension_files[0])) {
            $file = $extension_files[0];
        } else {
            $file = DIR_TEMPLATE . $template;
        }

        if (is_file($file)) {
            extract($this->data);

            ob_start();

            require($file);

            return ob_get_clean();
        }

        trigger_error('Error: Could not load template ' . $file . '!');
        exit();
    }

}