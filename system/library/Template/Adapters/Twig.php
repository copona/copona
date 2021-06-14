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

    protected $paths = [];

    public function __construct()
    {
        $config = \Registry::config();
        $this->config = $config->get('template.adapters.twig', []);

        $paths[] = DIR_TEMPLATE;

        // $fileSystem = new \Twig_Loader_Filesystem($paths);

        // $this->twig = new \Twig_Environment($fileSystem, [
        //     'autoescape' => isset($this->config['autoescape']) ? $this->config['autoescape'] : false,
        //     'cache'      => isset($this->config['cache']) && $this->config['cache'] ? DIR_CACHE_PRIVATE . '/twig' : false,
        //     'debug'      => isset($this->config['debug']) ? $this->config['debug'] : false,
        // ]);


        // $twig->addExtension(new \Twig\Extension\DebugExtension());

        // $this->twig = $twig;


        //register twig extensions
        // if (isset($this->config['extensions']) && is_array($this->config['extensions'])) {
        //     foreach ($this->config['extensions'] as $extension) {
        //         $this->twig->addExtension(new $extension);
        //     }
        // }
    }

    public function getExtensionsSupport()
    {
        return ['twig', 'html.twig', 'tpl'];
    }


    public function addPath($path)
    {
        // TODO: Sanity, security checks?
        // TODO: This is overhead to ALWAYS add path, because usually this loader is already loaded and added!
        //// must be optimized to load path only once!

        $this->paths[] = $path;

    }

    public function render($template, array $data)
    {


        // TODO: cache needed!
        if (!defined('DIR_CATALOG')) {
            $extension_files = glob(dirname(DIR_SYSTEM) . "/extensions/*/*/catalog/view/theme/default/template/" . $template);
        } else {
            $extension_files = glob(dirname(DIR_SYSTEM) . "/extensions/*/*/admin/view/template/" . $template);
        }

        if (\Config::get('config_theme') == 'theme_default') {
            $theme = \Config::get('theme_default_directory');
        } else {
            $theme = \Config::get('config_theme');
        }

        // First, let's check for Extension template
        // if (!empty($extension_files[0])) {
        //     $file = $extension_files[0];
        // } else {
        //     if (!defined('DIR_CATALOG')) {
        //         // non-admin (catalog)
        //         $file = DIR_TEMPLATE . \Config::get('parent_theme', $theme) . '/template/' . $template;
        //     } else {
        //         // admin
        //         $theme = "";
        //         $file = DIR_TEMPLATE . \Config::get('parent_theme', $theme) . $template;
        //     }
        //
        // }

        $file = $template;

        // pr($template);
        // pr(\Config::get('parent_theme', $theme));
        //
        //
        // prd(DIR_PUBLIC . "/themes/" . \Config::get('theme_default_directory') . "/");

        if (endsWith($template, '.twig')) {

            $loader = new \Twig\Loader\FilesystemLoader(
                [
                    DIR_PUBLIC . "/themes/" . \Config::get('theme_default_directory') . "/",
                ]
            );

            $loader->addPath(DIR_PUBLIC . "/themes/" . \Config::get('theme_default_directory') . "/", 'theme');



            foreach($this->paths as $path){
                $loader->addPath( $path );
            }

            // prd(DIR_PUBLIC . "/themes/" . \Config::get('theme_default_directory') . "/");

            $twig = new \Twig\Environment($loader, [
                'debug' => true,
                //'cache' => '/path/to/compilation_cache',
            ]);


            $twig->addFunction(new \Twig\TwigFunction('strip2words', function ($a, $b) {
                return strip2words($a, $b);
            }));

            $twig->addFunction(new \Twig\TwigFunction('pr', function ($a, $b = 0) {
                return pr($a, $b);
            }));

            $twig->addFunction(new \Twig\TwigFunction('prd', function ($a, $b = 0) {
                return prd($a, $b);
            }));

            $twig->addExtension(new \Twig\Extension\DebugExtension());

            if (class_exists('\voku\helper\HtmlMin')) {
                $htmlMin = new \voku\helper\HtmlMin();
                $htmlMin->doOptimizeViaHtmlDomParser(0);               // optimize html via "HtmlDomParser()"
                // prd( $htmlMin->minify( $twig->render($template, $this->data) ) ) ;
                // return $htmlMin->minify( $twig->render($template, $this->data) );
            }


            return $twig->render($template, $data);

        } else {
            // .tpl templeitiem!
            if (is_file($file)) {
                extract($data);
                ob_start();
                require($file);
                return ob_get_clean();
            } else {
                prd("$file does not exists! ");
            }
        }

        trigger_error('Error: Could not load template ' . $file . '!');
        exit();


    }
}
