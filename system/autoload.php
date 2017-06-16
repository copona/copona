<?php

// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Libraries Autoload
function library($class)
{
    $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($file)) {
        include_once(modification($file));

        return true;
    } else {
        return false;
    }
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

// Helper
require_once(DIR_SYSTEM . 'helper/debug.php');
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/text.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');