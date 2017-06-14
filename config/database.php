<?php

return [
    'database' => [

        'default_connection' => 'default',

        /**
         * Database
         * mpdo, mssql, mysql, mysqli or postgre
         */
        'default' => [
            'db_type'      => env('DB_DRIVER', ''),
            'db_hostname'  => DB_HOSTNAME,
            'db_username'  => DB_USERNAME,
            'db_password'  => DB_PASSWORD,
            'db_database'  => DB_DATABASE,
            'db_port'      => DB_PORT,
        ]
    ]
];