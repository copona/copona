<?php

$ips = Config::get('debug.allow_ip', []);
$debug_mode = Config::get('debug.mode');

if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
    $client_ip = $_SERVER["REMOTE_ADDR"];
} else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $client_ip = $_SERVER["HTTP_CLIENT_IP"];
}

if (count($ips) && array_search($client_ip, $ips) === false) {
    $debug_mode = false;
}

if (!function_exists('pr')) {

    function pr($data = 'w/o variable', $vardump = false)
    {
        if (@$GLOBALS['debug_mode']) {
            echo "\n\n";
            echo "<div style='border: 1px solid grey; padding: 5px;'>";
            echo "<span style='color: black; background-color: white; font-size: 12px;'>\nPR data: <strong>" . gettype($data) . "</strong></span>\n";
            echo "<pre style='background-color: #EACCCC; white-space: pre-wrap; font-size: 14px; color: red; padding: 10px; margin: 0; line-height: 14px;'>\n";

            if ($data === '') {
                echo "EMPTY STRING\n";
            } elseif ($data === ' ') {
                echo "SAPCE\n";
            } elseif ($data === 0) {
                echo " 0 \n";
            } elseif ($data === false) {
                echo "FALSE \n";
            } elseif ($data === null) {
                echo "UNDEFINED\n";
            } elseif (gettype($data) == 'string') {
                echo !$vardump ? htmlentities($data) : $data;
            } else {
                print_r($data);
            }
            echo "\n</pre>\n";

            $debug = debug_backtrace();

            $file_from = file($debug[0]['file']);

            foreach ($debug as $file) {
                echo "<span style='font-size: 12px;'>\n<strong>" . trim($file_from[$debug[0]['line'] - 1]) . "</strong>\n</span><br />\n";
                echo "<span style='font-size: 12px;'>" . $file['file'] . "</span>:\n";
                echo "<span style='font-size: 12px; color: red; font-weight: bold;'>" . $file['line'] . "</span> <br />\n";
                break;
            }
            echo "</div>";
        }
    }

}

if (!function_exists('prd')) {

    function prd($data = 'w/o variable', $vardump = false)
    {
        if (@$GLOBALS['debug_mode']) {
            echo "\n\n";
            echo "<div style='border: 1px solid grey; padding: 5px;'>";
            echo "<span style='color: black; background-color: white; font-size: 12px;'>\nPRD data: <strong>" . gettype($data) . "</strong></span>\n";
            echo "<pre style='white-space: pre-wrap; background-color: #ccc; padding: 10px;  font-size: 14px; color: black; margin: 0; line-height: 14px;'>\n";

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
            echo "\n</pre>\n";

            $debug = debug_backtrace();
            $file_from = file($debug[0]['file']);

            foreach ($debug as $file) {
                echo "<span style='font-size: 12px;'>\n<strong>" . trim($file_from[$debug[0]['line'] - 1]) . "</strong>\n</span><br />\n";
                echo "<span style='font-size: 12px;'>" . $file['file'] . "</span>:\n";
                echo "<span style='font-size: 12px; color: red; font-weight: bold;'>" . $file['line'] . "</span> <br />\n";
                break;
            }
            echo "</div>";
            die();
        }
    }

}

if (!function_exists('dt')) {

    function dt()
    {
        if (@$GLOBALS['debug_mode']) {
            !isset($_SESSION['dt_start_time']) ? $_SESSION['dt_start_time'] = microtime(true) : false;
            $diff = microtime(true) - $_SESSION['dt_start_time'];
            return $diff;
        }
    }
}