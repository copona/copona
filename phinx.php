<?php

require_once __DIR__ . "/config.php";

$migration_path = __DIR__ . "/migrations";

return [
    "paths"        => [
        "migrations" => $migration_path
    ],
    "environments" => [
        "default_migration_table" => DB_PREFIX . "migrations",
        "default_database"        => 'default',
        'default'                 => [
            "adapter"      => "mysql",
            "host"         => DB_HOSTNAME,
            "name"         => DB_DATABASE,
            "user"         => DB_USERNAME,
            "pass"         => DB_PASSWORD,
            "port"         => DB_PORT,
            "table_prefix" => DB_PREFIX
        ]
    ]
];