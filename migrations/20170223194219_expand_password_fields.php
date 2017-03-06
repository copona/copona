<?php

use Phinx\Migration\AbstractMigration;

class ExpandPasswordFields extends AbstractMigration
{
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
    public function up()
    {
        $this->table('user')
                    ->changeColumn('password', 'string', array('limit' => 255))
                    ->addIndex(array('username'))
                    ->save();
        $this->table('customer')
                    ->changeColumn('password', 'string', array('limit' => 255))
                    ->addIndex(array('email'))
                    ->save();
        $this->table('affiliate')
                    ->changeColumn('password', 'string', array('limit' => 255))
                    ->addIndex(array('email'))
                    ->save();
    }

    public function down()
    {

// This migration is irreversible. Once the password column is used, it will have more than 40 characters of data.
//
//        $this->table('user')
//                    ->changeColumn('password', 'string', array('limit' => 40))
//                    ->removeIndexByName('username')
//                    ->save();
//        $this->table('customer')
//                    ->changeColumn('password', 'string', array('limit' => 40))
//                    ->removeIndexByName('email')
//                    ->save();
//        $this->table('affiliate')
//                    ->changeColumn('password', 'string', array('limit' => 40))
//                    ->removeIndexByName('email')
//                    ->save();
    }

}
