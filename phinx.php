<?php

if (!defined('DIR_COPONA')) {
    define('DIR_PUBLIC', __DIR__);
    require_once DIR_PUBLIC . "/system/startup.php";
}

$migration_path = DIR_PUBLIC . "/migrations";

$config = new ConfigManager(DIR_CONFIG);

$default_connection = $config->get('database.default_connection') ? $config->get('database.default_connection') : 'default';

$db_config = $config->get('database.' . $default_connection);

if (!defined('DB_PREFIX')) {
    define('DB_PREFIX', $db_config['db_prefix']);
}

return [
    "paths"        => [
        "migrations" => $migration_path
    ],
    "environments" => [
        "default_migration_table" => DB_PREFIX . "migrations",
        "default_database"        => 'default',
        'default'                 => [
            "adapter"      => "mysql",
            "host"         => $db_config['db_hostname'],
            "name"         => $db_config['db_database'],
            "user"         => $db_config['db_username'],
            "pass"         => $db_config['db_password'],
            "port"         => $db_config['db_port'],
            "table_prefix" => DB_PREFIX
        ]
    ]
];