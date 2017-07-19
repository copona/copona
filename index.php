<?php
//Public dir
define('DIR_PUBLIC', __DIR__);

//Set application
define('APPLICATION', 'catalog');

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start(APPLICATION);

// Output
global $response;
$response->setCompression($config->get('config_compression'));
$response->output();