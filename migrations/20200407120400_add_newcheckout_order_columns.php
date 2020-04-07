<?php

use Phinx\Migration\AbstractMigration;

class AddNewcheckoutOrderColumns extends AbstractMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $order = $this->table('order');

        if (!$order->hasColumn('payment_email')) {
            $order
                ->addColumn('payment_email', 'string', ['null' => true, 'limit'=>63, 'after' => 'payment_lastname']);
        }


        if (!$order->hasColumn('payment_telephone')) {
            $order
                ->addColumn('payment_telephone', 'string', ['null' => true, 'limit'=>63, 'after' => 'payment_email']);
        }


        if (!$order->hasColumn('payment_address_different')) {
            $order
                ->addColumn('payment_address_different', 'integer', ['null' => true, 'after' => 'custom_field', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY]);
        }

        $order->update();
    }
}
