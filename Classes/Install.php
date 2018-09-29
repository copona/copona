<?php

namespace Copona\Classes;

use \Phinx\Console\PhinxApplication;
use \Phinx\Wrapper\TextWrapper;

class Install
{
    /**
     * Check if project installed
     *
     * @return bool
     */
    public static function checkIfInstalled()
    {
        return file_exists(DIR_PUBLIC . '/.env');
    }

    /**
     * Create .env file
     *
     * @param array $data
     */
    public static function createDotEnv(array $data)
    {
        $output = '';
        foreach ($data as $key => $item) {
            $output .= $key . '=' . $item . PHP_EOL;
        }

        if (!file_exists(DIR_PUBLIC . '/.env')) {
            touch(DIR_PUBLIC . '/.env');
        }

        $file = fopen(DIR_PUBLIC . '/.env', 'w');
        fwrite($file, $output);
        fclose($file);
    }

    /**
     * Write file database.php
     *
     * @param $env
     * @param array $data
     */
    public static function createDatabaseConfig($env, array $data)
    {
        $output = "<?php

return [
    'database' => [

        'default_connection' => 'default',

        /**
         * Database
         * mpdo, mssql, mysql, mysqli or postgre
         */
        'default'            => [
            'db_type'     => '" . addslashes($data['db_driver']) . "',
            'db_hostname' => '" . addslashes($data['db_hostname']) . "',
            'db_username' => '" . addslashes($data['db_username']) . "',
            'db_password' => '" . addslashes(html_entity_decode($data['db_password'], ENT_QUOTES, 'UTF-8')) . "',
            'db_database' => '" . addslashes($data['db_database']) . "',
            'db_prefix'   => '" . addslashes($data['db_prefix']) . "',
            'db_port'     => '" . addslashes($data['db_port']) . "',
        ]
    ]
];
";

        $file = DIR_PUBLIC . '/config/' . $env . '/database.php';

        @mkdir(dirname($file), \Config::get('directory_permission', 0755), true);

        if (!file_exists($file)) {
            touch($file);
        }

        $file = fopen($file, 'w');
        fwrite($file, $output);
        fclose($file);
    }

    /**
     * Import init structure db
     *
     * @param array $data
     * @throws \Exception
     */
    public static function database(array $data)
    {
        $db = new \DB($data['db_driver'], htmlspecialchars_decode($data['db_hostname']),
            htmlspecialchars_decode($data['db_username']), htmlspecialchars_decode($data['db_password']),
            htmlspecialchars_decode($data['db_database']), $data['db_port']);

        $file = DIR_PUBLIC . '/migrations/structure.sql';

        if (!file_exists($file)) {
            throw new \Exception('Could not load sql file: ' . $file);
        }

        $lines = file($file);

        if ($lines) {
            $sql = '';

            foreach ($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql .= $line;

                    if (preg_match('/;\s*$/', $line)) {
                        $sql = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'],
                            $sql);
                        $sql = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $sql);
                        $sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql);
                        $sql = str_replace("ALTER TABLE `oc_", "ALTER TABLE `" . $data['db_prefix'], $sql);

                        $db->query($sql);

                        $sql = '';
                    }
                }
            }

            $db->query("SET CHARACTER SET utf8");

            $db->query("SET @@session.sql_mode = 'MYSQL40'");

            $db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");

            $db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', password = '" . password_hash($data['password'],
                    PASSWORD_DEFAULT) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1', date_added = NOW()");
            $db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
            $db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");

            $db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
            $db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(token(1024)) . "'");

            $db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

            $db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET name = 'Default', `key` = '" . $db->escape(token(256)) . "', status = 1, date_added = NOW(), date_modified = NOW()");

            $api_id = $db->getLastId();

            $db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
            $db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");

            //Enable seo url if .htaccess exist
            if (file_exists(DIR_PUBLIC . '/.htaccess')) {
                $db->query("UPDATE `" . $data['db_prefix'] . "setting` SET value = '1' WHERE `key` = 'config_seo_url'");
            }
        }
    }


    /**
     * Execute phinx migration
     *
     * @return string
     */
    public static function migration()
    {
        //migrations path inside extension
        $_SERVER['PHINX_MIGRATION_PATH'] = realpath(DIR_PUBLIC . '/migrations');

        //init phinx
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app);

        //set parser
        $wrap->setOption('parser', 'php');

        //set config file
        $wrap->setOption('configuration', DIR_PUBLIC . '/phinx.php');

        return $wrap->getMigrate('default');
    }

    /**
     * Execute phinx rollback
     *
     * @return string
     */
    public static function rollback()
    {
        //migrations path inside extension
        $_SERVER['PHINX_MIGRATION_PATH'] = realpath(DIR_PUBLIC . '/migrations');

        //init phinx
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app);

        //set parser
        $wrap->setOption('parser', 'php');

        //set config file
        $wrap->setOption('configuration', DIR_PUBLIC . '/phinx.php');

        return $wrap->getRollback('default');
    }
}
