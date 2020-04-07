<?php


/*
 * TODO: deprecated! Should be possible to Use Laravel foundation helper, found in:
 * vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:937
 * renamed for now.
 * */
if (!function_exists('__copona')) {
    function __copona($string, $domain = '') {
        return Translation::get($string);
    }
}

if (!function_exists('__c')) {
    function __c($string, $domain = '') {
        return Translation::get($string);
    }
}
