<?php

namespace Copona\System\Library\Template\Adapters;

use Copona\System\Library\Template\Interfaces\TemplateAdapterInterface;

class Twig implements TemplateAdapterInterface
{
    /**
     * Configs twig
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct()
    {
        $config = \Registry::config();
        $this->config = $config->get('template.adapters.twig', []);

        $paths[] = DIR_TEMPLATE;

        $fileSystem = new \Twig_Loader_Filesystem($paths);

        $this->twig = new \Twig_Environment($fileSystem, [
            'autoescape' => isset($this->config['autoescape']) ? $this->config['autoescape'] : false,
            'cache'      => isset($this->config['cache']) && $this->config['cache'] ? DIR_CACHE_PRIVATE . '/twig' : false,
            'debug'      => isset($this->config['debug']) ? $this->config['debug'] : false
        ]);

        //register twig extensions
        if (isset($this->config['extensions']) && is_array($this->config['extensions'])) {
            foreach ($this->config['extensions'] as $extension) {
                $this->twig->addExtension(new $extension);
            }
        }
    }

    public function getExtensionsSupport()
    {
        return ['twig', 'html.twig', 'tpl'];
    }

    public function render($template, Array $data)
    {
        try {

            $template = str_replace(DIR_TEMPLATE, '', $template);

            extract($data);
            ob_start();

            $output = $this->twig->render($template, $data);

            eval(' ?>' . $output);
            $output = ob_get_contents();
            ob_end_clean();

            return $output;

        } catch (\Twig_Error_Syntax $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode());
        }
    }
}