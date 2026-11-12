<?php

use Phinx\Migration\AbstractMigration;

/**
 * Affiliates get a password reset link like customers (#206) instead of having
 * their password replaced and e-mailed by anyone who knows their address.
 * `code` is the affiliate's tracking code, so the reset code needs its own column.
 */
class AffiliateResetCode extends AbstractMigration
{
    public function up()
    {
        $prefix = DB_PREFIX;

        $column = $this->fetchRow("SHOW COLUMNS FROM `{$prefix}affiliate` LIKE 'reset_code'");

        if (!$column) {
            $this->execute("ALTER TABLE `{$prefix}affiliate` ADD `reset_code` varchar(64) NOT NULL DEFAULT '' AFTER `salt`");
        }
    }

    public function down()
    {
        $this->execute("ALTER TABLE `" . DB_PREFIX . "affiliate` DROP COLUMN `reset_code`");
    }
}
