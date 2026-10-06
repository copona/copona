<?php

use Phinx\Migration\AbstractMigration;

/**
 * Unregisters extensions whose code was removed because the services behind
 * them shut down (Payza, G2A Pay, Pilibaba, Paymate, Lay-Buy, legacy Klarna
 * Account/Invoice + Klarna Fee, Google Hangouts).
 *
 * Without this, a store that had one of them installed and enabled would
 * fatal at checkout trying to load the now-missing model.
 *
 * Order/transaction tables created by these extensions (g2apay_order,
 * laybuy_transaction, pilibaba_order, ...) are intentionally kept: they hold
 * historical payment records.
 */
class RemoveDeadPaymentGateways extends AbstractMigration
{
    private const CODES = [
        'payza',
        'g2apay',
        'pilibaba',
        'pilibaba_button',
        'paymate',
        'laybuy',
        'laybuy_layout',
        'klarna_account',
        'klarna_invoice',
        'klarna_fee',
        'google_hangouts',
    ];

    public function up()
    {
        $prefix = DB_PREFIX;
        $codes  = "'" . implode("','", self::CODES) . "'";

        $this->execute("DELETE FROM `{$prefix}extension` WHERE `code` IN ({$codes})");
        $this->execute("DELETE FROM `{$prefix}setting` WHERE `code` IN ({$codes})");
        $this->execute("DELETE FROM `{$prefix}module` WHERE `code` IN ({$codes})");

        foreach (self::CODES as $code) {
            $this->execute("DELETE FROM `{$prefix}layout_module` WHERE `code` = '{$code}' OR `code` LIKE '{$code}.%'");
        }
    }

    public function down()
    {
        // Removed extensions cannot be restored; nothing to undo.
    }
}
