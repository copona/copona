<?php

use Phinx\Migration\AbstractMigration;

class BannerImageDescription extends AbstractMigration
{
    public function change()
    {
        $exists = $this->hasTable('banner_image');
        if ($exists) {
            $banner_image_table = $this->table('banner_image');

            if (!$banner_image_table->hasColumn('description')) {
                $banner_image_table
                    ->addColumn('description', 'text')
                    ->save();

                $banner_image_table->changeColumn('description', 'text', [
                    'encoding'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci'
                ]);

            }
        }

        return true;
    }
}
