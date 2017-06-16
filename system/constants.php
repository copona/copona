<?php

// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', 'dev');

if(!defined('APPLICATION')) {
    define('APPLICATION', basename(realpath('')) ? basename(realpath('')) : 'catalog');
}

if(APPLICATION != 'install') {

    //Get port
    $server_port = '';
    if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != 80) && $_SERVER['SERVER_PORT'] != 443) {
        $server_port = ':' . $_SERVER['SERVER_PORT'];
    }

    //define domain url constant
    define('DOMAIN_NAME', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] . $server_port : null);
    $parse_url = parse_url($_SERVER['SCRIPT_NAME']);
    define('BASE_URI', str_replace(['index.php', '//'], '', $parse_url['path']));
    define('BASE_URL', DOMAIN_NAME . BASE_URI);
    define('BASE_URL_CATALOG', (str_replace(['index.php', 'admin', '//'], '', BASE_URL)));

    // HTTP
    define('HTTP_SERVER', 'http://' . BASE_URL);
    define('HTTP_CATALOG', 'http://' . BASE_URL_CATALOG);

    // HTTPS
    define('HTTPS_SERVER', 'https://' . BASE_URL);
    define('HTTPS_CATALOG', 'https://' . BASE_URL_CATALOG);

    // DIR
    define('DIR_APPLICATION', DIR_PUBLIC . '/' . APPLICATION . '/');
    define('DIR_CATALOG', DIR_PUBLIC . '/catalog/');
    define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
    define('DIR_IMAGE', DIR_PUBLIC . '/image/');
    define('DIR_LANGUAGE', DIR_PUBLIC . '/'.APPLICATION.'/language/');

    if(APPLICATION == 'catalog') {
        define('DIR_TEMPLATE', DIR_PUBLIC . '/catalog/view/theme/');
    } else {
        define('DIR_TEMPLATE', DIR_PUBLIC . '/admin/view/template/');
    }

    define('DIR_CONFIG', DIR_PUBLIC . '/config/');
    define('DIR_CACHE', DIR_PUBLIC . '/system/storage/cache/');
    define('DIR_DOWNLOAD', DIR_PUBLIC . '/system/storage/download/');
    define('DIR_LOGS', DIR_PUBLIC . '/system/storage/logs/');
    define('DIR_MODIFICATION', DIR_PUBLIC . '/system/storage/modification/');
    define('DIR_UPLOAD', DIR_PUBLIC . '/system/storage/upload/');
}