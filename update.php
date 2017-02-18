<?php
/*
 * Update script.
 *
 * this is just quick hack fix for this.
 *
 * TODO: Make it as PDO, with checks!
 *
 * index.php must check, if some "version" value of DB is the same, as curent some locally saved variable.
 * With every sql change, devs must change some variable in version file. If it differs from temp variable, then
 * index.php redirects user to update.php.
 *
 * this file must be SECURE, available only if this variable differs, and SQL updates must be NON-INTERACTIVE,
 * every sql change, at this momen, MUST be checked ans securely executed whatsover.
 */

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$count = 0;

echo "<pre>";

// **************************
// 2017-02-01
// add TOP to information table

$table = DB_PREFIX . "information";
$column = 'top';
if (!$db->query("SHOW COLUMNS FROM `" . $table . "` LIKE '" . $column . "'")->num_rows) {
    $sql = "ALTER TABLE `" . $table . "` ADD `" . $column . "` INT(1) NOT NULL DEFAULT '0' AFTER `bottom`";
    echo $sql . "<br /> // started ...";
    $db->query($sql);
    echo "finished\n</br>";
    $count++;
}

// **************************
// 2017-02-01
// Images for Information pages
// Create table if not exists.
$table = DB_PREFIX . "information_image";
if (!$db->query("SHOW TABLES LIKE '" . $table . "'")->num_rows) {
    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` ( `information_image_id` INT NOT NULL AUTO_INCREMENT , `information_id` INT NOT NULL , `image`
    VARCHAR(255) NOT NULL , `sort_order` INT(3) NOT NULL , PRIMARY KEY (`information_image_id`)) COLLATE utf8_general_ci";
    echo $sql . "<br /> // started ...";
    $db->query($sql);
    echo "finished\n</br>";
    $count++;
}
// ALTER TABLE `oc_information` ADD `image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `information_id`;
$table = DB_PREFIX . "information";
$column = 'image';
if (!$db->query("SHOW COLUMNS FROM `" . $table . "` LIKE '" . $column . "'")->num_rows) {
    $sql = "ALTER TABLE `" . $table . "` ADD `" . $column . "` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `information_id`";
    echo $sql . "<br /> // started ...";
    $db->query($sql);
    echo "finished\n</br>";
    $count++;
}
echo "</pre>";

// prd($result);

echo "<h1>Completed " . $count . " updates/changes!</h1>";
