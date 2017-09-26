<?php

namespace Copona\Classes;

use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;

class Migration
{
    /**
     * Prepare phinx migration
     *
     * @param $migration_path
     * @return TextWrapper
     */
    protected static function prepareMigration($migration_path)
    {
        //migrations path inside extension
        $_SERVER['PHINX_MIGRATION_PATH'] = $migration_path;

        //init phinx
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app);

        //set parser
        $wrap->setOption('parser', 'php');

        //set config file
        $wrap->setOption('configuration', DIR_PUBLIC . '/phinx.php');

        return $wrap;
    }

    /**
     * Exec migration
     *
     * @param $migration_path
     * @return string
     */
    public static function migrate($migration_path)
    {
        $wrap = self::prepareMigration($migration_path);

        //execute migrate
        return $wrap->getMigrate('default');
        die();
    }

    /**
     * rollback migration
     *
     * @param $migration_path
     * @param int $target
     * @return string
     */
    public static function rollback($migration_path, $target = 0)
    {
        $wrap = self::prepareMigration($migration_path);

        //execute rollback
        return $wrap->getRollback('default', $target);
        die();
    }
}