<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ChangeMetadataColumnSize extends AbstractMigration {

    /**
     * Migrate Up.
     */
    public function up() {
        $users = $this->table('content_meta');
        $users->changeColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down() {

    }

}