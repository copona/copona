<?php
$ip = $_SERVER['REMOTE_ADDR'];

if ($ip == '192.168.1.1' OR
	strpos($ip, "127.0.0.") === 0 OR
	strpos($ip, "192.168.0.") === 0) {
	define('DEBUG', true);
} else {
	define('DEBUG', false);
}