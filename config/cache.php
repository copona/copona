<?php

return [
    'cache' => [

        'enable' => env('CACHE_ENABLE', false),

        /**
         * Drivers support:
         *
         * Apc
         * Apcu
         * Cassandra
         * Cookie
         * Couchbase
         * Couchdb
         * Devfalse
         * Devnull
         * Devtrue
         * Files
         * Leveldb
         * Memcache
         * Memcached
         * Memstatic
         * Mongodb
         * Predis
         * Redis
         * Riak
         * Sqlite
         * Ssdb
         * Wincache
         * Xcache
         * Zend Disk Cache
         * Zend Memory Cache
         *
         * Custom Driver, set complete namespace class your driver example PathNamespace\CustomDriver
         * and extends of Copona\Cache\CacheBase
         * For more drivers descriptions https://github.com/PHPSocialNetwork/phpfastcache/blob/master/docs/DRIVERS.md
         */
        'driver' => 'Files',

        /**
         * The configuration depends on each driver selected, see the allowed settings
         * https://www.phpfastcache.com/#config-options
         */
        'configs' => [
            "securityKey"         => 'file',
            "path"                => DIR_PUBLIC . '/' . PATH_CACHE_PRIVATE,
            "default_chmod"       => 0755,
            "htaccess"            => true,
            "defaultTtl"          => 3600,
            'ignoreSymfonyNotice' => true,
        ]
    ]
];