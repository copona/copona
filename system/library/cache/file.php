<?php

namespace Cache;

class File
{
    private $expire;

    public function __construct()
    {
        $this->expire = \Config::get('cache.cache_expire', 3600);

        if (!is_dir(DIR_CACHE_PRIVATE)) {
            mkdir(DIR_CACHE_PRIVATE, \Config::get('directory_permission', 0777), true);
        }

        $files = glob(DIR_CACHE_PRIVATE . 'cache.*');

        if ($files) {
            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);

                // $time - is set "in the future", from CACHE_EXPIRE_TIME when cached file was created.
                // delete, if cache expired, or new cache is less then previous.
                if ($time < time() || ($time > time() + $this->expire ) ) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }

    public function get($key)
    {
        $files = glob(DIR_CACHE_PRIVATE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            $handle = fopen($files[0], 'r');

            flock($handle, LOCK_SH);

            if(!filesize($files[0]))
                return false;

            $data = fread($handle, filesize($files[0]));

            flock($handle, LOCK_UN);

            fclose($handle);

            return json_decode($data, true);
        }

        return false;
    }

    public function set($key, $value)
    {
        $this->delete($key);

        $file = DIR_CACHE_PRIVATE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '',
            $key) . '.' . (time() + $this->expire);

        $handle = fopen($file, 'w');

        flock($handle, LOCK_EX);

        fwrite($handle, json_encode($value));

        fflush($handle);

        flock($handle, LOCK_UN);

        fclose($handle);
    }

    public function delete($key)
    {
        $files = glob(DIR_CACHE_PRIVATE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

}