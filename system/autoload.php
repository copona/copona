<?php

// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Custom autoload
use \Copona\System\Support\ClassLoader;

ClassLoader::register();
ClassLoader::addDirectories([
    DIR_PUBLIC . '/themes',
    DIR_PUBLIC . '/extensions'
]);

/**
 * Libraries Autoload
 * @TODO change this to PSR-4
 *
 * @param $class
 * @return bool
 */
function library($class)
{
    $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', $class) . '.php';

    if (is_file($file)) {
        include_once(modification($file));
        return true;
    } else {

        $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

        if (is_file($file)) {
            include_once(modification($file));
            return true;
        } else {
            return false;
        }
    }
}

spl_autoload_register('library');
spl_autoload_extensions('.php');