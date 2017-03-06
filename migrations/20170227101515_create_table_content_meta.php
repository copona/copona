<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTableContentMeta extends AbstractMigration {

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change() {
        $exists = $this->hasTable('content_meta');
        if (!$exists) {
            $content_meta = $this->table('content_meta', ['id' => 'content_meta_id' ]);
            $content_meta
                ->setOptions([
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci'
                ])
                ->addColumn('content_type', 'string', ['null' => false, 'limit' => 255 ])
                ->addColumn('content_id', 'integer', ['null' => false, 'limit' => 55 ])
                ->addColumn('value', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_LONG ])
                ->create();
        }
    }

}