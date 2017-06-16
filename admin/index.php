<?php

//Public dir
define('DIR_PUBLIC', realpath(__DIR__ . '/../'));

//Set application
define('APPLICATION', 'admin');

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start(APPLICATION);