<?php

use Phinx\Migration\AbstractMigration;

class CreateTableImagesForInformationPages extends AbstractMigration {

    /**
     * Images for Information pages
     * Create table if not exists.
     */
    public function change() {
        $exists = $this->hasTable('information_image');
        if (!$exists) {
            $information_image = $this->table('information_image', ['id' => 'information_image_id' ]);
            $information_image
                ->addColumn('information_id', 'integer', ['null' => false, 'limit' => 11 ])
                ->addColumn('image', 'string', ['null' => false, 'limit' => 255 ])
                ->addColumn('sort_order', 'integer', ['null' => false, 'limit' => 3 ])
                ->save();
        }
    }

}