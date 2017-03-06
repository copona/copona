<?php

use Phinx\Migration\AbstractMigration;

class InformationExternalLink extends AbstractMigration {

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
        $exists = $this->hasTable('information_description');
        if ($exists) {
            $information_description = $this->table('information_description');

            if (!$information_description->hasColumn('external_link')) {
                $information_description
                    ->addColumn('external_link', 'string', ['null' => true])
                    ->save();
            }
        }
    }

}