<?php

class Config extends \Noodlehaus\Config
{
    public function __construct($config_path)
    {
        $paths[] = $config_path;

        if (env('APP_ENV') && is_dir(DIR_CONFIG . env('APP_ENV') . '/')) {
            $paths[] = DIR_CONFIG . env('APP_ENV') . '/';
        }

        parent::__construct($paths);
    }
}