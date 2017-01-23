<?php

return [

    'general' => [

        // Site
        'site_ssl' => false,

        'debug' => true,

        'storage'               => [
            'storage_path'      => '/storage',
            'upload_path'       => '/storage/upload',
            'image_path'        => '/storage/image',
            'download_path'     => '/storage/download',
            'modification_path' => '/storage/modification',
        ],

        // Url
        'url_autostart'         => true,

        // Language (DIRECTORY!)
        'language_default'      => 'en',
        'language_autoload'     => ['en'],

        // Error
        'config_error_display'  => true,
        'config_error_log'      => true,
        'config_error_filename' => 'error.log',
        'log_path'              => 'storage/logs',

        // Reponse
        'response_header'       => ['Content-Type: text/html; charset=utf-8'],
        'response_compression'  => 0,

        // Autoload Configs
        'config_autoload'       => [],

        // Autoload Libraries
        'library_autoload'      => [],

        // Autoload Libraries
        'model_autoload'        => [],
    ]
];