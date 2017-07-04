<?php

class Config
{
    /**
     * @var \Noodlehaus\Config
     */
    private static $config;

    /**
     * @param $config
     */
    public static function setConfig($config)
    {
        self::$config = $config;
    }

    /**
     * @return \Noodlehaus\Config
     */
    public static function getConfig()
    {
        if (null === self::$config) {
            self::$config = new ConfigManager(DIR_CONFIG);
        }

        return self::$config;
    }

    /**
     * Get config
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return self::getConfig()->get($key, $default);
    }

    /**
     * Set value in config
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set($key, $value)
    {
        self::getConfig()->set($key, $value);
    }

    /**
     * Check has config
     *
     * @param $key
     * @return mixed
     */
    public static function has($key)
    {
        return self::getConfig()->has($key);
    }

    /**
     * Get all config
     *
     * @return mixed
     */
    public static function all()
    {
        return self::getConfig()->all();
    }
}