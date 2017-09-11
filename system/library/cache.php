<?php

class Cache
{
    private $adaptor;

    public function __construct($adaptor)
    {
        $class = 'Cache\\' . $adaptor;

        if (class_exists($class)) {
            $this->adaptor = new $class(Config::get('cache.cache_expire'));
        } else {
            throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
        }
    }

    public function get($key)
    {
        return $this->adaptor->get($key);
    }

    public function set($key, $value)
    {
        return $this->adaptor->set($key, $value);
    }

    public function delete($key)
    {
        return $this->adaptor->delete($key);
    }

    public function flush()
    {
        if (defined('CACHE_DRIVER') && (CACHE_DRIVER == 'memcached') && $this->ismemcache) {
            $this->memcache->flush(); // DELETE all cache
        }

        // Filesystem cache also is cleared ALWAYS.
        // Sometimes You miss admin config.php correct cache settings.
        //else
        //{
        /********* CAREFUL AJ start ************/
         if (class_exists('VQMod')) {

            $vqmod_cache = array();
            $vqmod_cache[] = DIR_VQMOD_CACHE . 'checked.cache';
            $vqmod_cache[] = DIR_VQMOD_CACHE . 'mods.cache';

            if ($vqmod_cache) {
                foreach ($vqmod_cache as $val) {
                    if (file_exists($val)) {
                        @unlink($val);
                        clearstatcache();
                    }
                }
            }

            //Salipinām .php un .tpl failus no vqcache direktorijas.
            $vqmod_cache = array_merge(glob(DIR_VQMOD_CACHE . 'vqcache/' . '*.php'), glob(DIR_VQMOD_CACHE . 'vqcache/' . '*.tpl'));

            if ($vqmod_cache) {
                foreach ($vqmod_cache as $val) {
                    if (file_exists($val)) {
                        @unlink($val);
                        clearstatcache();
                    }
                }
            }

        }

        if(defined('DIR_CACHE_PRIVATE')) {
            $files_all = glob(DIR_CACHE_PRIVATE . 'cache.*');

            if ($files_all) {
                foreach ($files_all as $file) {
                    if (file_exists($file)) {
                        @unlink($file);
                        clearstatcache();
                    }
                }
            }
        } else {
             $this->log->write('Dache DIR_CACHE_PRIVATE constante not set!');
        }
        /********* CAREFUL AJ end ************/
        //}

    }


}