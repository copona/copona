<?php

use Phinx\Migration\AbstractMigration;

class AddColumbForFreeDownload extends AbstractMigration
{
    public function change()
    {
        $download = $this->table('download');

        if (!$download->hasColumn('is_free')) {
            $download
                ->addColumn('is_free', 'integer', [
                    'null' => false,
                    'default' => 0,
                    'after' => 'date_added',
                    'limit' => 1
                ]);
        }

        $download->update();
    }
}
