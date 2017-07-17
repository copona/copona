<?php
// Error Reporting
error_reporting(E_ALL);

require_once DIR_PUBLIC . '/system/constants.php';
require_once DIR_PUBLIC . '/system/autoload.php';

if (!function_exists('env')) {
    /**
     * Get Env
     *
     * @param      $key
     * @param null $default
     * @return array|false|null|string
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && $value[0] == '"' && $value[strlen($value) - 1] == '"') {
            return substr($value, 1, -1);
        }

        //check is array
        if (strlen($value) > 1 && $value[0] == '[' && $value[strlen($value) - 1] == ']') {
            $value = explode(',', substr($value, 1, -1));
            return array_filter($value);
        }

        return $value;
    }
}

// Check if Installed
if (\Copona\Classes\Install::checkIfInstalled() == false
    && APPLICATION != 'core' && APPLICATION != 'install'
    && is_dir(DIR_PUBLIC . '/install/')
) {
    header('Location: install/index.php');
    exit;
}

//Dotenv
if (file_exists(DIR_PUBLIC . '/.env')) {
    $dotenv = new Dotenv\Dotenv(DIR_PUBLIC);
    $dotenv->load();
}

//Init Config
$config = new ConfigManager(DIR_CONFIG);
$GLOBALS['config'] = $config;

// Helper
require_once(DIR_SYSTEM . 'helper/debug.php');
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/text.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');

//Errors handler
if ($config->get('debug.mode') == true) {
    $whoops = new \Whoops\Run;
    if (Whoops\Util\Misc::isAjaxRequest()) { //ajax
        $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
    } else if (Whoops\Util\Misc::isCommandLine()) { //command line
        $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
    } else { //html
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    }
    $whoops->register();
}

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
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
    $_SERVER['HTTPS'] = true;
} else if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') {
    $_SERVER['HTTPS'] = true;
} else if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Universal Host redirect to correct hostname
if (defined('HTTP_HOST') && defined('HTTPS_HOST') && $_SERVER['HTTP_HOST'] != parse_url(HTTPS_SERVER)['host'] && $_SERVER['HTTP_HOST'] != parse_url(HTTP_SERVER)['host']) {
    header("Location: " . ($_SERVER['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . ltrim('/', $_SERVER['REQUEST_URI']));
}

// Modification Override
function modification($filename)
{
    if (defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    } elseif (defined('DIR_COPONA')) {
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

// Engine
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/hook.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));

function start($application_config)
{
    require_once(DIR_SYSTEM . 'framework.php');
}