<?php

use Copona\Security\FieldCipher;
use Phinx\Migration\AbstractMigration;

/**
 * Encrypts affiliate bank payout details (#83) with APP_KEY from .env.
 *
 * - Widens the bank_* columns: an encrypted value is ~140 characters.
 * - Creates APP_KEY in .env when the store doesn't have one yet (installs
 *   older than this change), so existing rows can be encrypted right away.
 * - Encrypts every non-empty plaintext value.
 *
 * Keep .env backed up: without APP_KEY the encrypted details can't be read
 * (affiliates would have to re-enter them).
 */
class EncryptAffiliateBankDetails extends AbstractMigration
{
    public function up()
    {
        $prefix = DB_PREFIX;

        foreach (FieldCipher::AFFILIATE_FIELDS as $field) {
            $this->execute("ALTER TABLE `{$prefix}affiliate` MODIFY `{$field}` varchar(255) NOT NULL DEFAULT ''");
        }

        if (FieldCipher::key() === null && !$this->createAppKey()) {
            $this->getOutput()->writeln('<comment>APP_KEY missing and .env not writable: affiliate bank details left unencrypted. Add APP_KEY to .env and re-save them.</comment>');

            return;
        }

        $columns = '`affiliate_id`, `' . implode('`, `', FieldCipher::AFFILIATE_FIELDS) . '`';

        foreach ($this->fetchAll("SELECT {$columns} FROM `{$prefix}affiliate`") as $row) {
            $set = [];

            foreach (FieldCipher::AFFILIATE_FIELDS as $field) {
                if ($row[$field] !== '' && $row[$field] !== null && !FieldCipher::isEncrypted($row[$field])) {
                    $set[] = "`{$field}` = " . $this->getAdapter()->getConnection()->quote(FieldCipher::encrypt($row[$field]));
                }
            }

            if ($set) {
                $this->execute("UPDATE `{$prefix}affiliate` SET " . implode(', ', $set) . " WHERE `affiliate_id` = " . (int)$row['affiliate_id']);
            }
        }
    }

    public function down()
    {
        // Decrypt back to plaintext (columns stay varchar(255)).
        $prefix = DB_PREFIX;
        $columns = '`affiliate_id`, `' . implode('`, `', FieldCipher::AFFILIATE_FIELDS) . '`';

        foreach ($this->fetchAll("SELECT {$columns} FROM `{$prefix}affiliate`") as $row) {
            $set = [];

            foreach (FieldCipher::AFFILIATE_FIELDS as $field) {
                if (FieldCipher::isEncrypted($row[$field])) {
                    $set[] = "`{$field}` = " . $this->getAdapter()->getConnection()->quote(FieldCipher::decrypt($row[$field]));
                }
            }

            if ($set) {
                $this->execute("UPDATE `{$prefix}affiliate` SET " . implode(', ', $set) . " WHERE `affiliate_id` = " . (int)$row['affiliate_id']);
            }
        }
    }

    /**
     * Appends a new APP_KEY to .env and makes it available to this process.
     */
    private function createAppKey(): bool
    {
        $file = DIR_PUBLIC . '/.env';

        if (!is_file($file)) {
            return false;
        }

        $contents = (string)file_get_contents($file);

        // Already in .env but not loaded into this process (e.g. the installer
        // wrote .env and runs migrations in the same request): use it.
        if (preg_match('/^APP_KEY=(\S+)\s*$/m', $contents, $match)) {
            $_ENV['APP_KEY'] = trim($match[1], '"\'');
            putenv('APP_KEY=' . $_ENV['APP_KEY']);

            return FieldCipher::key() !== null;
        }

        if (!is_writable($file)) {
            return false;
        }

        $key = FieldCipher::generateKey();
        $separator = ($contents === '' || str_ends_with($contents, "\n")) ? '' : PHP_EOL;

        if (file_put_contents($file, $separator . 'APP_KEY=' . $key . PHP_EOL, FILE_APPEND) === false) {
            return false;
        }

        $_ENV['APP_KEY'] = $key;
        putenv('APP_KEY=' . $key);
        $this->getOutput()->writeln('<info>Created APP_KEY in .env (back it up: it decrypts affiliate bank details).</info>');

        return true;
    }
}
