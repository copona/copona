<?php

require_once "loadtime.php";
loadTime::start();

// Error Reporting
error_reporting(E_ALL);

require_once DIR_PUBLIC . '/system/constants.php';

//env function
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





// debug hack endd
if (!function_exists('pr')) {

    function pr($data = 'w/o variable', $vardump = false, $prd = false, $plaintext = false )
    {
        if (@$GLOBALS['debug_mode']) {
            echo "\n\n";
            $html = "<div style='border: 1px solid grey; padding: 5px;'>";
            $html .= "<span style='color: black; background-color: white; font-size: 12px;'>\n" . ($prd ? 'prd' : 'pr') . " data: <strong>" . gettype($data) . "</strong></span>\n";
            $html .= "<pre style='white-space: pre-wrap; background-color: " . ($prd ? 'grey' : '#EACCCC') . "; padding: 10px;  font-size: 14px; color: black; margin: 0; line-height: 14px;'>\n";

            ob_start();
            if ($data === '') {
                echo "empty STRING\n";
            } elseif ($data === ' ') {
                echo "empty SPACE\n";
            } elseif ($data === 0) {
                echo " 0 \n";
            } elseif ($data === false) {
                echo "FALSE \n";
            } elseif ($data === null) {
                echo "UNDEFINED\n";
            } elseif (gettype($data) == 'string') {
                echo !$vardump ? htmlentities($data) : $data;
            } else {
                $vardump ? array_walk_recursive($data, function (&$v) {
                    $v = htmlspecialchars($v);
                }) : false;
                $vardump ? var_dump($data) : print_r($data);
            }

            $result = ob_get_contents();
            ob_end_clean();

            $html .= $result . "\n</pre>\n";

            $debug = debug_backtrace();

            // if called FROM PRD, then line index will be +1
            if($prd) {
                $fileindex = 1;
            } else {
                $fileindex = 0;
            }
            $file_from = file($debug[$fileindex]['file']);

            foreach ($debug as $file) {
                $html .= "<span style='font-size: 12px;'>\n<strong>" . trim($file_from[$debug[$fileindex]['line'] - 1]) . "</strong>\n</span><br />\n";
                $html .= "<span style='font-size: 12px;'>" . $debug[$fileindex]['file'] . "</span>:\n";
                $html .= "<span style='font-size: 12px; color: red; font-weight: bold;'>" . $debug[$fileindex]['line'] . "</span> <br />\n";
                break;
            }
            $html .= "</div>";

            if(!isset($_SERVER['SHELL']) && !$plaintext ) {
                echo $html;
            }  else {
                echo "/************ start ****************/\n\n";
                echo $result ."\n";
                echo "\n/************* end *****************/\n";
                echo trim($file_from[$debug[$fileindex]['line'] - 1]) ."\n";
                echo $debug[$fileindex]['file'] .":" . $debug[$fileindex]['line'] ."\n";
            }


        }
    }

}


if (!function_exists('prd')) {

    function prd($data = 'w/o variable', $vardump = false, $bulk = false, $palintext = false )
    {
        if (@$GLOBALS['debug_mode']) {
            pr( $data, $vardump, true, $palintext );

            // DIE, only if we are not in CLI.

            if(php_sapi_name() === 'cli') {
                $handle = fopen ("php://stdin","r");
                $line = fgets($handle);
                if(trim($line) != 'yes'){
                    echo "Continuing!!\n";
                }
                fclose($handle);
            } else {
                die();
            }
        }
    }

}

//Composer autoload
require_once DIR_PUBLIC . '/system/autoload.php';

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
    $dotenv = Dotenv\Dotenv::create(DIR_PUBLIC);
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
require_once(DIR_SYSTEM . 'helper/translation.php');

//Errors handler
if ($config->get('debug.mode') == true) {
    $whoops = new \Whoops\Run;
    if (Whoops\Util\Misc::isAjaxRequest()) { //ajax
        $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
    } else {
        if (Whoops\Util\Misc::isCommandLine()) { //command line
            $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
        } else { //html
            $handler = new \Whoops\Handler\PrettyPageHandler;
            foreach (['_ENV', '_GET', '_POST', '_COOKIE', '_SERVER', '_SESSION'] as $global) {
                $handler->blacklist($global, 'DB_PASSWORD');
                $handler->blacklist($global, 'DB_HOSTNAME');
                $handler->blacklist($global, 'DEBUG_ALLOW_IP');
            }
            $whoops->pushHandler($handler);
        }
    }
    $whoops->register();
}

// Check Version
if (version_compare(phpversion(), '7.1.0', '<') == true) {
    exit('PHP7.1+ Required');
}

// Set Default Timezone
if (strcmp($config->get('config_timezone'), ini_get('date.timezone'))) {
    $timezone = $config->get('config_timezone') ? $config->get('config_timezone') : ini_get('date.timezone');
    date_default_timezone_set( $timezone );
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

// Correct Client IP @ https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
    $client_ip = $_SERVER["REMOTE_ADDR"];
} else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_CLIENT_IP"];
}

$_SERVER['HTTP_CLIENT_IP'] = $client_ip;

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
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));

function start($application_config)
{
    require_once(DIR_SYSTEM . 'framework.php');
}
