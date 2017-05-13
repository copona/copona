<?php

use Phinx\Migration\AbstractMigration;

class ChangeSettingTableColumnSizesCodeKey extends AbstractMigration
{
   /**
     * Migrate Up.
     */
    public function up() {
        $users = $this->table('setting');
        $users->changeColumn('key', 'string', ['limit' => 128 ])
            ->changeColumn('code', 'string', ['limit' => 128 ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down() {

    }
}
