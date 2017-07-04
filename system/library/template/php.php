<?php

namespace Template;

final class PHP
{
    private $data = array();

    public function __construct($registry)
    {
        $this->config = $registry->get('config');
        //$this->db = $registry->get('db');
        //$this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->request = $registry->get('request');
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function render($template)
    {
        // pr($template);
        // TODO: optimize this!
        if(APPLICATION == 'catalog') {
            $extension_files = glob(DIR_PUBLIC . "/extensions/*/*/themes/" . $template);
        } else {
            $extension_files = glob(DIR_PUBLIC . "/extensions/*/*/admin/view/template/" . $template);
        }

        // First, let's check for Extension template
        if (!empty($extension_files[0])) {
            $file = $extension_files[0];
        } else {
            $file = DIR_TEMPLATE . $template;
        }

        if (is_file($file)) {
            extract($this->data);

            ob_start();

            require($file);

            return ob_get_clean();
        } else {
            throw new \Exception('Error: Could not load template ' . $file . '!');
        }
    }
}