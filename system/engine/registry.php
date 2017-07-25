<?php

class Registry extends \Illuminate\Container\Container
{
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function get($key)
    {
        return $this->make($key);
    }

    public function set($key, $value)
    {
        $this->instance($key, $value);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->get($name);
    }

    public function has($key)
    {
        return isset($this->instances[$key]) ? true : false;
    }
}