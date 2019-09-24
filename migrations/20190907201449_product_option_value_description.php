<?php

use Phinx\Migration\AbstractMigration;

class ProductOptionValueDescription extends AbstractMigration
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
    public function up()
    {

        $order_status = $this->table('product_option_value');

        if (!$order_status->hasColumn('description')) {
            $order_status
                ->addColumn('description', 'string', ['limit' => 254, 'null' => false, 'default' => '']);
        }

        $order_status->update();
    }
}
