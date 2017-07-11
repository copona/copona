<?php

namespace Copona\Core\Helpers;


class Util
{
    /**
     * Path root project
     *
     * @return string
     */
    public static function pathRoot()
    {
        if(!defined('DIR_PUBLIC')) {
            define('DIR_PUBLIC', realpath(__DIR__ . '/../../../../../'));
        }

        return DIR_PUBLIC . '/';
    }

    /**
     * Remove all files and folders recursive
     * @param $dir
     */
    public static function recursiveRemove($dir)
    {
        $structure = glob(rtrim($dir, "/") . '/*');
        if (is_array($structure)) {
            foreach ($structure as $file) {
                if (is_dir($file)) {
                    self::recursiveRemove($file);
                } elseif (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        @rmdir($dir);
    }
}