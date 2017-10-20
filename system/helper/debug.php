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
} elseif( is_array($ips) && array_search($client_ip, $ips) ) {
    $debug_mode = true;
}


// if PHP is accessed from http://php.net/manual/en/reserved.variables.server.php#92121
if (isset($_SERVER['SHELL'])) {
    $debug_mode = true;
    $GLOBALS['debug_mode'] = true;
}

if (!function_exists('pr')) {

    function pr($data = 'w/o variable', $vardump = false, $prd = false )
    {
        if (@$GLOBALS['debug_mode']) {
            echo "\n\n";
            $html = "<div style='border: 1px solid grey; padding: 5px;'>";
            $html .= "<span style='color: black; background-color: white; font-size: 12px;'>\nPRD data: <strong>" . gettype($data) . "</strong></span>\n";
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

            if(!isset($_SERVER['SHELL']) ) {
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

    function prd($data = 'w/o variable', $vardump = false)
    {
        if (@$GLOBALS['debug_mode']) {
            pr( $data, $vardump, true );
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