<?php
// Error Reporting
error_reporting(E_ALL);

// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Check Version
if (version_compare(phpversion(), '5.6.0', '<') == true) {
    exit('PHP5.6+ Required');
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Universal Host redirect to correct hostname
if (defined('HTTP_HOST') && defined('HTTPS_HOST') && $_SERVER['HTTP_HOST'] != parse_url(HTTPS_SERVER)['host'] && $_SERVER['HTTP_HOST'] != parse_url(HTTP_SERVER)['host']) {
    header("Location: " . ($_SERVER['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . ltrim('/', $_SERVER['REQUEST_URI']));
}

// Modification Override
function modification($filename) {
    if (defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    } elseif (defined('DIR_OPENCART')) {
        $file = DIR_MODIFICATION . 'install/' . substr($filename, strlen(DIR_APPLICATION));
    } else {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
    }

    if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

function library($class) {
    $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($file)) {
        include_once(modification($file));

        return true;
    } else {
        return false;
    }
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/hook.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'engine/proxy.php'));

// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');
require_once(DIR_SYSTEM . 'helper/debug.php');

function start($application_config) {
    require_once(DIR_SYSTEM . 'framework.php');
}
