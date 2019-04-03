<?php



function __($string, $domain = '') {
	return Translation::get($string);
}