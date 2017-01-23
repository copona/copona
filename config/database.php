<?php

return [
    'database' => [

        'adapter' => \Copona\Core\System\Library\Database\Adapters\Eloquent::class,

        'default' => 'mysql',

        'db_autostart' => true,

        'connections' => [

            'sqlite' => [
                'driver'   => 'sqlite',
                'database' => 'storage/database.sqlite',
                'prefix'   => '',
            ],

            'mysql' => [
                'driver'    => 'mysql',
                'host'      => 'database',
                'port'      => '3306',
                'database'  => 'copona',
                'username'  => 'root',
                'password'  => 'root',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],

            'pgsql' => [
                'driver'   => 'pgsql',
                'host'     => 'localhost',
                'port'     => '',
                'database' => 'database',
                'username' => 'root',
                'password' => '',
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
            ],

            'sqlsrv' => [
                'driver'   => 'sqlsrv',
                'host'     => 'localhost',
                'port'     => '',
                'database' => 'database',
                'username' => 'root',
                'password' => '',
                'prefix'   => '',
            ],
        ]
    ]
];