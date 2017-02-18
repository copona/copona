<?php

use Phinx\Migration\AbstractMigration;

class AddTopToInformationTable extends AbstractMigration
{
    /**
     * add TOP to information table
     */
    public function change()
    {
        $exists = $this->hasTable('information');
        if ($exists) {
            $information_table = $this->table('information');

            if (!$information_table->hasColumn('top')) {
                $information_table
                    ->addColumn('top', 'integer', ['null' => false, 'default' => 0, 'after' => 'bottom'])
                    ->save();
            }

        }
    }
}