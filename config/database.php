<?php

return [
    'database' => [

        'default_connection' => 'default',

        /**
         * Database
         * mpdo, mssql, mysql, mysqli or postgre
         */
        'default'            => [
            'db_type'     => env('DB_DRIVER', 'mysqli'),
            'db_hostname' => env('DB_HOSTNAME', 'localhost'),
            'db_username' => env('DB_USERNAME', 'root'),
            'db_password' => env('DB_PASSWORD', 'root'),
            'db_database' => env('DB_DATABASE', 'copona'),
            'db_prefix'   => env('DB_PREFIX', 'cp_'),
            'db_port'     => env('DB_PORT', '3306'),
        ]
    ]
];