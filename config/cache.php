<?php

return [
    'cache' => [

        'enable'       => env('CACHE_ENABLE', false),

        'cache_type'   => 'file', // apc, file or mem
        'cache_expire' => env('CACHE_EXPIRE', 3600),
    ]
];