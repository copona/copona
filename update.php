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
// 2017-02-01
// add TOP to information table
if (!$db->query("SHOW COLUMNS FROM " . DB_PREFIX . "information LIKE 'top'")->num_rows) {
	$sql = "ALTER TABLE `" . DB_PREFIX . "information` ADD `top` INT(1) NOT NULL DEFAULT '0' AFTER `bottom`";
	echo $sql . "<br /> // started ...";
	$db->query($sql);
	echo "finished\n</br>";
	$count++;
}

// 2017-02-01
// Images for Information pages
// ALTER TABLE `oc_information` ADD `image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `information_id`;
if (!$db->query("SHOW COLUMNS FROM " . DB_PREFIX . "information LIKE 'image'")->num_rows) {
	$sql = "ALTER TABLE `" . DB_PREFIX . "information` ADD `image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `information_id`";
	echo $sql . "<br /> // started ...";
	$db->query($sql);
	echo "finished\n</br>";
	$count++;
}
echo "</pre>";

// prd($result);

echo "<h1>Completed " . $count . " updates/changes!</h1>";
