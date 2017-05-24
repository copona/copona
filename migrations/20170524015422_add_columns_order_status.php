<?php

use Phinx\Migration\AbstractMigration;

class AddColumnsOrderStatus extends AbstractMigration
{
    public function change()
    {
        $order_status = $this->table('order_status');

        if (!$order_status->hasColumn('description')) {
            $order_status
                ->addColumn('description', 'text', ['null' => false]);
        }

        if (!$order_status->hasColumn('send_invoice')) {
            $order_status
                ->addColumn('send_invoice', 'integer', [
                    'null' => false,
                    'default' => 0,
                    'after' => 'description',
                    'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY
                ]);
        }

        $order_status->update();
    }
}