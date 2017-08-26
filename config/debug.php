<?php

return [

    'debug' => [

        /**
         * Debug mode
         */
        'mode'     => env('DEBUG_MODE', false),

        /**
         * Allow ips to enable debug
         */
        'allow_ip' => env_array('DEBUG_ALLOW_IP', []),
    ]
];