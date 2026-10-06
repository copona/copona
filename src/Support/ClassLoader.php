<?php

namespace Copona\Support;


class ClassLoader
{
    protected static $directories = array();

    protected static $registered = false;

    public static function load($class)
    {
        $class = static::normalizeClass($class);

        foreach (static::$directories as $directory) {
            if (is_file($path = $directory . DIRECTORY_SEPARATOR . $class)) {
                require_once $path;

                return true;
            }
        }
    }

    public static function normalizeClass($class)
    {
        /*
         * Lowercase folders
         */
        $class = explode('\\', $class);
        $file = array_pop($class);
        $class = strtolower(implode('\\', $class)) . '\\' . $file;

        // Strip first slash
        if ($class[0] == '\\') $class = substr($class, 1);

        return str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    }

    public static function register()
    {
        if (!static::$registered) {
            static::$registered = spl_autoload_register(array(ClassLoader::class, 'load'));
        }
    }

    public static function addDirectories($directories)
    {
        static::$directories = array_merge(static::$directories, (array)$directories);

        static::$directories = array_unique(static::$directories);
    }

    public static function removeDirectories($directories = null)
    {
        if (is_null($directories)) {
            static::$directories = array();
        } else {
            $directories = (array)$directories;

            static::$directories = array_filter(static::$directories, function ($directory) use ($directories) {
                return !in_array($directory, $directories);
            });
        }
    }

    public static function getDirectories()
    {
        return static::$directories;
    }
}