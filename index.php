<?php
// Version
define('VERSION', '2.3.0.3_rc');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');