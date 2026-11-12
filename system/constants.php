<?php

// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', 'dev');

if (!defined('APPLICATION')) {
    define('APPLICATION', basename(realpath('')) ? basename(realpath('')) : 'catalog');
}

//Get port
$server_port = '';
$_http_host = $_SERVER['HTTP_HOST'] ?? null;
// HTTP_HOST already contains the port when a non-standard port is used (e.g. "localhost:8080").
// Only append SERVER_PORT when HTTP_HOST doesn't include one, to avoid "host:port:port" duplication.
if ($_http_host && !str_contains($_http_host, ':') && isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
    $server_port = ':' . $_SERVER['SERVER_PORT'];
}

// Site URL(s) from APP_URL in .env: a comma-separated list of scheme://host[:port],
// e.g. "https://domain.lv,https://www.domain.lv,https://shop.domain.lv". The entry
// whose host matches the request is used, otherwise the first (canonical) one. The
// scheme always comes from that entry, so links and e-mails (password reset!) never
// echo a forged Host header or downgrade to http://. Without APP_URL the URL is
// derived from the request, as before.
$_app_scheme = null;
$_app_host = null;
$_app_urls = array_values(array_filter(array_map('trim', explode(',', (string)($_ENV['APP_URL'] ?? getenv('APP_URL') ?: '')))));

if ($_app_urls) {
    $_request_host = strtolower(preg_replace('/:(80|443)$/', '', $_http_host . $server_port));

    foreach ($_app_urls as $_i => $_app_url) {
        $_parts = parse_url($_app_url);

        if (empty($_parts['host']) || !in_array(strtolower($_parts['scheme'] ?? ''), ['http', 'https'], true)) {
            continue;
        }

        $_host = strtolower($_parts['host']) . (isset($_parts['port']) ? ':' . $_parts['port'] : '');
        $_host = preg_replace('/:(80|443)$/', '', $_host);

        if ($_app_host === null || $_host === $_request_host) {
            $_app_scheme = strtolower($_parts['scheme']);
            $_app_host = $_host;
        }

        if ($_host === $_request_host) {
            break;
        }
    }
}

if ($_app_host !== null) {
    define('DOMAIN_NAME', $_app_host);
} else {
    // No APP_URL: trust the request (legacy behaviour).
    define('DOMAIN_NAME', $_http_host ? $_http_host . $server_port : null);
}

$parse_url = parse_url($_SERVER['SCRIPT_NAME']);
define('BASE_URI', str_replace(['index.php', '//'], '', $parse_url['path']));
define('BASE_URL', DOMAIN_NAME . BASE_URI);
define('BASE_URL_CATALOG', (str_replace(['index.php', 'admin', '//'], '', BASE_URL)));
define('BASE_URL_IMAGE', rtrim((str_replace(['index.php', 'admin', '//'], '', BASE_URL)),'/') . "/image");

if (APPLICATION != 'install') {

    // With APP_URL both use its scheme, so links never depend on how the request arrived.
    $_http_scheme = $_app_scheme ?? 'http';
    $_https_scheme = $_app_scheme ?? 'https';

    // HTTP
    define('HTTP_SERVER', $_http_scheme . '://' . rtrim(BASE_URL, '/') . '/');
    define('HTTP_CATALOG', $_http_scheme . '://' . rtrim(BASE_URL_CATALOG, '/') . '/');

    // HTTPS
    define('HTTPS_SERVER', $_https_scheme . '://' . rtrim(BASE_URL, '/') . '/');
    define('HTTPS_CATALOG', $_https_scheme . '://' . rtrim(BASE_URL_CATALOG, '/') . '/');

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
    define('DIR_UPLOAD', DIR_STORAGE_PRIVATE . 'upload/');
}