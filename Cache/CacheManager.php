<?php

namespace Copona\Cache;

use Phpfastcache\Helper\Psr16Adapter;

class CacheManager extends Psr16Adapter
{
    public function flush(): bool
    {
        return $this->clear();
    }

    public function set($key, $value, $ttl = null): bool
    {
        if (\Config::get('cache.enable', false)) {
            return parent::set($key, $value, $ttl);
        }
        return false;
    }

    public function get($key, $default = null): mixed
    {
        if (\Config::get('cache.enable', false)) {
            return parent::get($key, $default);
        }
        return null;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        if (\Config::get('cache.enable', false)) {
            return parent::getMultiple($keys, $default);
        }
        return [];
    }

    public function setMultiple($values, $ttl = null): bool
    {
        if (\Config::get('cache.enable', false)) {
            return parent::setMultiple($values, $ttl);
        }
        return false;
    }
}
