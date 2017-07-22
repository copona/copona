<?php

namespace Copona\System\Library\Template\Adapters;

use Copona\System\Library\Template\Interfaces\TemplateAdapterInterface;

class Twig implements TemplateAdapterInterface
{
    public function getExtensionsSupport()
    {
        return ['twig', 'html.twig'];
    }

    public function render($template, Array $data)
    {
        $loader = new Twig_Loader_Array(array(
          'index' => 'Hello {{ name }}!',
        ));

        $twig = new Twig_Environment($loader);

        $file = DIR_TEMPLATE . $template;

        if (is_file($file)) {
            extract($data);

            ob_start();

            require($file);

            return ob_get_clean();
        }

        throw new \Exception('Error: Could not load template ' . $file . '!');
    }
}