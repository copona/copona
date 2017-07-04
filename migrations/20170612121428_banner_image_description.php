<?php

use Phinx\Migration\AbstractMigration;

class BannerImageDescription extends AbstractMigration
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
        $exists = $this->hasTable('banner_image');
        if ($exists) {
            $banner_image_table = $this->table('banner_image');

            if (!$banner_image_table->hasColumn('description')) {
                $banner_image_table
                    ->setOptions([
                        'charset'   => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci'
                    ])
                    ->addColumn('description', 'text')
                    ->save();
            }

        }
    }
}
