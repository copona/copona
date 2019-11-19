<?php


use Phinx\Migration\AbstractMigration;

class ImageUrlForImages extends AbstractMigration
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

        $tableAdapter = new \Phinx\Db\Adapter\TablePrefixAdapter($this->getAdapter());






        /* MAIN migration */
        $table = $this->table('product_image');
        if (!$table->hasColumn('image_url')) {
            $table
                ->addColumn('image_url', 'string', ['limit' => 600, 'null' => true, 'default' => ''])
            ;
        }
        $table->update();

        /* FIX for oc_image */
        $table = $this->table('product');

        echo "fff\n\n";
        $table
            ->changeColumn('date_modified', 'date', ['null' => 'false', 'default' => '1970-01-01'])
            ->changeColumn('date_available', 'date', ['null' => 'false', 'default' => '1970-01-01'])
            ;


        $this->execute("update {$tableAdapter->getAdapterTableName('product')} 
                set date_available = '1970-01-01' where date_available is null or date_available < '1970-01-01' or date_available < '0001-01-01' ");
        $this->execute("update {$tableAdapter->getAdapterTableName('product')} 
                set date_modified = '1970-01-01' where date_modified is null or date_modified < '1970-01-01' or date_modified < '0001-01-01' ");



        if (!$table->hasColumn('image_url')) {
            $table
                ->addColumn('image_url', 'string', ['limit' => 600, 'null' => true, 'default' => ''])
            ;
        }
        $table->update();


    }
}
