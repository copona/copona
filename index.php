<?php
//Public dir
define('DIR_PUBLIC', __DIR__);

//Set application
define('APPLICATION', 'catalog');

// Install
if (!file_exists(DIR_PUBLIC . '/.env') && is_dir(DIR_PUBLIC . '/install/')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start(APPLICATION);