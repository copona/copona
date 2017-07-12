<?php
//Public dir
define('DIR_PUBLIC', realpath(__DIR__ . '/../'));

//Set application
define('APPLICATION', 'admin');

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start(APPLICATION);

// Output
global $response;
$response->setCompression($config->get('config_compression'));
$response->output();