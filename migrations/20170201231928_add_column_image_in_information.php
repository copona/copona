<?php

use Phinx\Migration\AbstractMigration;

class AddColumnImageInInformation extends AbstractMigration
{
    /**
     * Add column image in information table
     */
    public function change()
    {
        $exists = $this->hasTable('information');
        if ($exists) {
            $information_table = $this->table('information');

            if (!$information_table->hasColumn('image')) {
                $information_table
                    ->addColumn('image', 'string', ['null' => true, 'after' => 'information_id'])
                    ->save();
            }

        }
    }
}