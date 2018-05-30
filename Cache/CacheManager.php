<?php

namespace Copona\Cache;


use phpFastCache\Helper\Psr16Adapter;

class CacheManager extends Psr16Adapter
{
    public function flush()
    {
        $this->clear();
    }

    public function set($key, $value, $ttl = null)
    {
        if (\Config::get('cache.enable', false)) {
            return parent::set($key, $value, $ttl);
        } else {
            return false;
        }
    }

    public function get($key, $default = null)
    {
        if (\Config::get('cache.enable', false)) {
            return parent::get($key, $default);
        } else {
            return null;
        }
    }

    public function getMultiple($keys, $default = null)
    {
        if (\Config::get('cache.enable', false)) {
            return parent::getMultiple($keys, $default);
        } else {
            return null;
        }
    }

    public function setMultiple($values, $ttl = null)
    {
        if (\Config::get('cache.enable', false)) {
            return parent::setMultiple($values, $ttl);
        } else {
            return null;
        }
    }
}