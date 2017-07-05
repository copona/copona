<?php
//Public dir
define('DIR_PUBLIC', realpath(__DIR__ . '/../'));

//Set application
define('APPLICATION', 'install');

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
    $protocol = 'https://';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

define('HTTP_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTPS_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_COPONA', $protocol . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

define('PATH_TEMPLATE', 'install/view/template/');
define('DIR_TEMPLATE', DIR_PUBLIC . '/' . PATH_TEMPLATE);

// DIR
define('DIR_COPONA', DIR_PUBLIC . '/');
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
define('DIR_IMAGE', DIR_PUBLIC . '/image/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
//define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_PUBLIC . '/config/');

define('PATH_STORAGE', 'storage/');
define('PATH_STORAGE_PUBLIC', PATH_STORAGE . 'public/');
define('PATH_STORAGE_PRIVATE', PATH_STORAGE . 'private/');
define('PATH_CACHE_PUBLIC', PATH_STORAGE_PUBLIC . 'cache/');
define('PATH_CACHE_PRIVATE', PATH_STORAGE_PRIVATE . 'cache/');

define('DIR_STORAGE_PUBLIC', DIR_PUBLIC . '/' . PATH_STORAGE_PUBLIC);
define('DIR_STORAGE_PRIVATE', DIR_PUBLIC . '/' . PATH_STORAGE_PRIVATE);

define('DIR_CACHE_PUBLIC', DIR_PUBLIC . '/' . PATH_CACHE_PUBLIC);
define('DIR_CACHE_PRIVATE', DIR_PUBLIC . '/' . PATH_CACHE_PRIVATE);

define('DIR_DOWNLOAD', DIR_STORAGE_PRIVATE . 'download/');
define('DIR_LOGS', DIR_STORAGE_PRIVATE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE_PRIVATE . 'modification/');
define('DIR_UPLOAD', DIR_STORAGE_PRIVATE . 'upload/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start(APPLICATION);