<?php


use Phinx\Migration\AbstractMigration;

class IndexesAndNullsForProductSpecials extends AbstractMigration
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
    public function change()
    {
        // ALTER TABLE `cp_product_special`
        // CHANGE `date_start` `date_start` date NULL AFTER `price`,
        // CHANGE `date_end` `date_end` date NULL AFTER `date_start`;


        $tableAdapter = new \Phinx\Db\Adapter\TablePrefixAdapter($this->getAdapter());

        $this->table('product_special')
             ->changeColumn('date_start', 'date', ['null' => true ])
             ->changeColumn('date_end', 'date', ['null' => true])
             ->save();
        $this->execute("update {$tableAdapter->getAdapterTableName('product_special')} set date_start = NULL where date_start < '1971-01-01'");
        $this->execute("update {$tableAdapter->getAdapterTableName('product_special')} set date_end = NULL where date_end < '1971-01-01'");

        $table = $this->table('product_special');
        $table->addIndex(['customer_group_id', 'date_start', 'date_end'])
              ->update();

    }
}
