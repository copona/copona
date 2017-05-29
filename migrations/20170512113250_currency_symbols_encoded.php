<?php

use Phinx\Migration\AbstractMigration;

class CurrencySymbolsEncoded extends AbstractMigration {

    /**
     * Migrate Up.
     */
    public function up() {
        $users = $this->table('currency');
        $users->changeColumn('symbol_left', 'string', ['limit' => 64 ])
            ->changeColumn('symbol_right', 'string', ['limit' => 64 ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down() {
        
    }

}