<?php

return [

    /**
     * App Env
     */
    'app_env'               => env('APP_ENV', 'production'),

    /**
     * Site
     */
    'site_base'             => HTTP_SERVER,
    'site_ssl'              => HTTPS_SERVER,

    /**
     * Permission Dir
     */
    'directory_permission'  => 0755,

    /**
     * Image cache path
     *
     * The public path where the thumbs will be generated
     */
    'image_cache_path'      => PATH_CACHE_PUBLIC . 'image/',

    /**
     * Image base URL
     *
     * You can modify the base url of the images
     * Do not add protocol https or http
     */
    'image_base_url'     => rtrim(BASE_URL_CATALOG, '/') . '/' . PATH_CACHE_PUBLIC . 'image',

    /**
     * Url
     */
    'url_autostart'         => true,

    /**
     * Language (DIRECTORY!)
     */
    'language_default'      => 'en',
    'language_autoload'     => ['en'],

    /**
     * Template
     */
    'template_type'         => 'php',

    /**
     * Error
     */
    'config_error_display'  => env('APP_ENV') == 'production' ? false : true,
    'config_error_log'      => true,
    'config_error_filename' => 'error.log',

    /**
     * Reponse
     */
    'response_header'       => ['Content-Type: text/html; charset=utf-8'],
    'response_compression'  => 0,

    /**
     * Autoload Configs
     */
    'config_autoload'       => [],

    /**
     * Autoload Libraries
     */
    'library_autoload'      => [],

    /**
     * Autoload model
     */
    'model_autoload'        => [],
];