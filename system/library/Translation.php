<?php
class Translation{

	private static $data = [];
	private static $registry;
	private static $log;

    public static function start() {
		self::$registry = Registry::getInstance();
		self::$log = new Log('missing.translations.log');

	}
    public static function get($string = '', $domain = '') {

		// just for debug. Shoudl be switched off on live:

		if(!self::$registry->language->has( $string )) {
			self::$log->write( "\n\nmissing: " . $string . "\n@ " . debug_backtrace()[1]['file'] . ':' . debug_backtrace()[1]['line'] );
		};

        return self::$registry->language->get( $string );
    }

}