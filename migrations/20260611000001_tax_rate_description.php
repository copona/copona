<?php

use Phinx\Migration\AbstractMigration;

class TaxRateDescription extends AbstractMigration
{
    public function up()
    {
        $prefix = DB_PREFIX;

        // Create the description table
        $this->execute("
            CREATE TABLE IF NOT EXISTS `{$prefix}tax_rate_description` (
                `tax_rate_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `name`        varchar(32) NOT NULL,
                PRIMARY KEY (`tax_rate_id`, `language_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Migrate existing names into the description table for every language
        $this->execute("
            INSERT IGNORE INTO `{$prefix}tax_rate_description` (tax_rate_id, language_id, name)
            SELECT tr.tax_rate_id, l.language_id, tr.name
            FROM `{$prefix}tax_rate` tr
            CROSS JOIN `{$prefix}language` l
        ");
    }

    public function down()
    {
        $prefix = DB_PREFIX;
        $this->execute("DROP TABLE IF EXISTS `{$prefix}tax_rate_description`");
    }
}
