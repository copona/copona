<?php



if(!isset( $_SERVER['SHELL'] )) {
    echo "Continue from console - run Phinx migration (currently - the only way to run it is from console!...";
    echo "<pre>vendor/bin/phinx migrate</pre>";
    die("\n\nInstallation probably done... Ensure to run Phinx!");
}

if (!defined('DIR_COPONA')) {
    if(!defined('DIR_PUBLIC')) {
        define('DIR_PUBLIC', __DIR__);
    }
    require_once DIR_PUBLIC . "/system/startup.php";
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists(DIR_PUBLIC . '/.env')) {
    $dotenv = Dotenv\Dotenv::create(DIR_PUBLIC);
    $dotenv->load();
}

if(isset($_SERVER['PHINX_MIGRATION_PATH']) && $_SERVER['PHINX_MIGRATION_PATH']) {
    $migration_path = $_SERVER['PHINX_MIGRATION_PATH'];
} else {
    $migration_path = DIR_PUBLIC . "/migrations";
}

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
