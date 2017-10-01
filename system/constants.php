<?php

// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', 'dev');

if (!defined('APPLICATION')) {
    define('APPLICATION', basename(realpath('')) ? basename(realpath('')) : 'catalog');
}

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
define('BASE_URL_IMAGE', (str_replace(['index.php', 'admin', '//'], '', BASE_URL)) . "image");

if (APPLICATION != 'install') {

    // HTTP
    define('HTTP_SERVER', 'http://' . rtrim(BASE_URL, '/') . '/');
    define('HTTP_CATALOG', 'http://' . rtrim(BASE_URL_CATALOG, '/') . '/');

    // HTTPS
    define('HTTPS_SERVER', 'https://' . rtrim(BASE_URL, '/') . '/');
    define('HTTPS_CATALOG', 'https://' . rtrim(BASE_URL_CATALOG, '/') . '/');

    // DIR
    define('DIR_APPLICATION', DIR_PUBLIC . '/' . APPLICATION . '/');
    define('DIR_CATALOG', DIR_PUBLIC . '/catalog/');
    define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
    define('DIR_IMAGE', DIR_PUBLIC . '/image/');
    define('DIR_LANGUAGE', DIR_PUBLIC . '/' . APPLICATION . '/language/');

    if (APPLICATION == 'catalog') {
        define('PATH_TEMPLATE', 'themes/');
        define('DIR_TEMPLATE', DIR_PUBLIC . '/' . PATH_TEMPLATE);
    } else {
        define('PATH_TEMPLATE', 'admin/view/template/');
        define('DIR_TEMPLATE', DIR_PUBLIC . '/' . PATH_TEMPLATE);
    }

    define('PATH_STORAGE', 'storage/');
    define('PATH_STORAGE_PUBLIC', PATH_STORAGE . 'public/');
    define('PATH_STORAGE_PRIVATE', PATH_STORAGE . 'private/');
    define('PATH_CACHE_PUBLIC', PATH_STORAGE_PUBLIC . 'cache/');
    define('PATH_CACHE_PRIVATE', PATH_STORAGE_PRIVATE . 'cache/');

    define('DIR_CONFIG', DIR_PUBLIC . '/config/');

    define('DIR_STORAGE_PUBLIC', DIR_PUBLIC . '/' . PATH_STORAGE_PUBLIC);
    define('DIR_STORAGE_PRIVATE', DIR_PUBLIC . '/' . PATH_STORAGE_PRIVATE);

    define('DIR_CACHE_PUBLIC', DIR_PUBLIC . '/' . PATH_CACHE_PUBLIC);
    define('DIR_CACHE_PRIVATE', DIR_PUBLIC . '/' . PATH_CACHE_PRIVATE);

    define('DIR_DOWNLOAD', DIR_STORAGE_PRIVATE . 'download/');
    define('DIR_LOGS', DIR_STORAGE_PRIVATE . 'logs/');
    define('DIR_MODIFICATION', DIR_STORAGE_PRIVATE . 'modification/');
    define('DIR_UPLOAD', DIR_STORAGE_PRIVATE . 'upload/');
}