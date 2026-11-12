<?php

use Phinx\Migration\AbstractMigration;

/**
 * Removes the OCMOD / extension-installer support.
 *
 * The Extension Installer, Extension Store, Modifications (OCMOD) and
 * design/translation pages let an admin upload or download a zip and have its
 * PHP files written into the webroot (directly or over FTP) and its OCMOD XML
 * patched into core code, with no signature or content checks. Their code is
 * gone; this cleans up what they left in the database:
 *
 * - the `modification` table (stored OCMOD XML),
 * - the `config_ftp_*` settings (incl. a plaintext FTP password) that only
 *   existed so the installer could push files over FTP,
 * - the removed routes in every user group's access/modify permissions.
 */
class RemoveOcmodInstaller extends AbstractMigration
{
    private const ROUTES = [
        'extension/installer',
        'extension/modification',
        'extension/store',
        'design/translation',
    ];

    public function up()
    {
        $prefix = DB_PREFIX;

        $this->execute("DROP TABLE IF EXISTS `{$prefix}modification`");
        $this->execute("DELETE FROM `{$prefix}setting` WHERE `code` = 'config' AND `key` LIKE 'config\\_ftp\\_%'");

        foreach ($this->fetchAll("SELECT `user_group_id`, `permission` FROM `{$prefix}user_group`") as $group) {
            $permission = json_decode($group['permission'], true);

            if (!is_array($permission)) {
                continue;
            }

            $changed = false;

            foreach (['access', 'modify'] as $type) {
                if (isset($permission[$type]) && is_array($permission[$type])) {
                    $kept = array_values(array_diff($permission[$type], self::ROUTES));

                    if (count($kept) !== count($permission[$type])) {
                        $permission[$type] = $kept;
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                $this->execute(sprintf(
                    "UPDATE `{$prefix}user_group` SET `permission` = %s WHERE `user_group_id` = %d",
                    $this->getAdapter()->getConnection()->quote(json_encode($permission)),
                    (int)$group['user_group_id']
                ));
            }
        }
    }

    public function down()
    {
        // The removed features are not coming back; nothing to restore.
    }
}
