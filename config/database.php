<?php

return [
    'database' => [

        'default_connection' => 'default',

        /**
         * Database
         * mpdo, mssql, mysql, mysqli or postgre
         */
        'connections'        => [

            'default' => [
                'db_type'      => env('DB_DRIVER', 'mysqli'),
                'db_hostname'  => env('DB_HOSTNAME', 'localhost'),
                'db_username'  => env('DB_USERNAME', 'root'),
                'db_password'  => env('DB_PASSWORD', 'root'),
                'db_database'  => env('DB_DATABASE', 'copona'),
                'db_prefix'    => env('DB_PREFIX', 'cp_'),
                'db_port'      => env('DB_PORT', '3306'),
                'db_charset'   => env('DB_CHARSET', 'utf8'),
                'db_collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            ],

            'wawa' => [
                'db_type'      => env('DB_DRIVER', 'mysqli'),
                'db_hostname'  => env('DB_HOSTNAME', 'localhost'),
                'db_username'  => env('DB_USERNAME', 'root'),
                'db_password'  => env('DB_PASSWORD', 'root'),
                'db_database'  => 'wawa',
                'db_prefix'    => env('DB_PREFIX', 'cp_'),
                'db_port'      => env('DB_PORT', '3306'),
                'db_charset'   => env('DB_CHARSET', 'utf8'),
                'db_collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            ],
        ]
    ]
];