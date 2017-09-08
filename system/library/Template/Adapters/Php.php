<?php

namespace Copona\System\Library\Template\Adapters;

use Copona\System\Library\Template\Interfaces\TemplateAdapterInterface;

class Php implements TemplateAdapterInterface
{
    public function getExtensionsSupport()
    {
        return ['tpl'];
    }

    public function render($template_file, Array $data)
    {
        if (is_file($template_file)) {
            extract($data);

            //TODO: just remove!
            if(empty($breadcrumbs)) $breadcrumbs = [];

            ob_start();

            include $template_file;

            return ob_get_clean();
        } else {
            throw new \RuntimeException('Error: Could not load template ' . $template_file . '!');
        }
    }

    public function __get($key)
    {
        return \Registry::$key();
    }
}