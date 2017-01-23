<?php

return [
    'cache' => [

        'adapter' => \Copona\Core\System\Library\Cache\Adapters\File::class,

        // Cache
        'cache_type'   => 'file', // apc, file or mem
        'cache_expire' => 3600,

        'cache_path' => '/storage/cache'
    ]
];