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
        'allow_ip' => env('DEBUG_ALLOW_IP', []),

        /**
         * Debug and log SQL queries into logs/mysql_queries.log
         */
        'sql'      => env('DEBUG_SQL', false),
    ]
];