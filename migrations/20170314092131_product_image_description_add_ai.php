<?php

use Phinx\Migration\AbstractMigration;

class ProductImageDescriptionAddAi extends AbstractMigration {

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
        //'ALTER TABLE `cp_product_image_description` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;'
        //   $table = $this->table('cp_product_image_description', array( ['id' => false, 'primary_key' => 'id'] ));
        //  $table->changeColumn('id', 'integer', ['identity' => true]);
        $this->table('product_image_description')
            ->changeColumn('id', 'integer', ['identity' => true ])
            ->save();
    }

}