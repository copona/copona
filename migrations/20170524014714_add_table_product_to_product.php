<?php

use Phinx\Migration\AbstractMigration;

class AddTableProductToProduct extends AbstractMigration
{
    public function change()
    {
        $exists = $this->hasTable('product_to_product');
        if (!$exists) {
            $product_to_product = $this->table('product_to_product', ['id' => 'product_group_id']);
            $product_to_product
                ->addColumn('product_group_id', 'integer', ['null' => false, 'limit' => 11])
                ->addColumn('product_id', 'integer', ['null' => false, 'limit' => 11])
                ->addColumn('default_id', 'integer', ['null' => false, 'limit' => 11, 'default' => 0])
                ->addIndex(array('product_group_id', 'product_id'), array('unique' => true))
                ->create();
        }
    }
}