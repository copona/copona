<?php

return [
    'template' => [

        /**
         * Default engine
         */
        'default'  => 'twig',

        /**
         * List from engines
         */
        'adapters' => [

            'php' => [

                /**
                 * Template Engine
                 * Implement Interface Copona\System\Library\Template\Interfaces\TemplateDriverInterface
                 */
                'adapter' => \Copona\System\Library\Template\Adapters\Php::class,
            ],

            'twig' => [

                /**
                 * Template Engine
                 * Implement Interface Copona\System\Library\Template\Interfaces\TemplateDriverInterface
                 */
                'adapter' => \Copona\System\Library\Template\Adapters\Twig::class,

                'extensions' => [
                    Twig_Extension_Debug::class,
                ],

                'cache'      => true,
                'autoescape' => false,
                'debug'      => true,
            ],
        ]
    ]
];