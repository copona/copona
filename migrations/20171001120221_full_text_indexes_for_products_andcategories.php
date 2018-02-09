<?php

use Phinx\Migration\AbstractMigration;

class FullTextIndexesForProductsAndcategories extends AbstractMigration
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

        $table = $this->table('product_description');
        $table->addIndex('name', ['type' => 'fulltext'])
            ->addIndex('description', ['type' => 'fulltext'])
            ->update();

        $table = $this->table('product');
        $table->addIndex('model', ['type' => 'fulltext'])
            ->update();

        $table = $this->table('category_description');
        $table->addIndex('name', ['type' => 'fulltext'])
            ->addIndex('description', ['type' => 'fulltext'])
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
