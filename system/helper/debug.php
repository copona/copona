<?php

$client_ip = '';
$ips = Config::get('debug.allow_ip', []);
$debug_mode = Config::get('debug.mode');

if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
    $client_ip = $_SERVER["REMOTE_ADDR"];
} else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_CLIENT_IP"];
}

if (is_array($ips) && count($ips) && array_search($client_ip, $ips) === false) {
    $debug_mode = false;
} elseif( is_array($ips) && array_search($client_ip, $ips) !== false ) {
    $debug_mode = true;
}


// if PHP is accessed from http://php.net/manual/en/reserved.variables.server.php#92121
if (isset($_SERVER['SHELL'])) {
    $debug_mode = true;
    $GLOBALS['debug_mode'] = true;
}

if (defined('DEBUG')) {
    $start_time = microtime();
    $start_mem = memory_get_usage();
}

/*
 * pr() and prd() functions moved "before" Composer autoload.php, because of CakePHP functions pr() and prd() conflict.
 *
 * */

if (!function_exists('dt')) {

    function dt( $int = false )
    {
        return loadTime::diff( $int );
        /*if (@$GLOBALS['debug_mode']) {
            !isset($_SESSION['dt_start_time']) ? $_SESSION['dt_start_time'] = microtime(true) : false;
            $diff = microtime(true) - $_SESSION['dt_start_time'];
            return $diff;
        }*/
    }
}

if (!function_exists('ddd')) {

    function ddd()
    {
        $i = 0;
        $output = '';
        while( !empty( debug_backtrace()[ $i ] ) ) {
            if( !empty( debug_backtrace()[$i]['file'] )){
                $output .= debug_backtrace()[$i]['file'].":".debug_backtrace()[$i]['line'] . " \n";
            }
            $i++;
        }

        return $output;
    }
}