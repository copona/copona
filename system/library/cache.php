<?php
class Cache {
    private $adaptor;

    public function __construct($adaptor) {
        $class = 'Cache\\' . $adaptor;

        if (class_exists($class)) {
            $this->adaptor = new $class(Config::get('cache_expire'));
        } else {
            throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
        }
    }

    public function get($key) {
        return $this->adaptor->get($key);
    }

    public function set($key, $value) {
        return $this->adaptor->set($key, $value);
    }

    public function delete($key) {
        return $this->adaptor->delete($key);
    }

}