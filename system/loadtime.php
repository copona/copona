<?php
class loadTime{
    private static $time_start     =   0;
    private static $time_end       =   0;
    private static $time           =   0;

    private function __construct() {}
    public static function start() {
        self::$time_start = microtime(true);
    }

    private static function milliseconds() {
        //$mt = explode(' ', microtime());
        //return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
        return microtime(true);
    }


    public static function diff(){
        self::$time_end = self::milliseconds();
        self::$time = self::$time_end - self::$time_start;
        return "Loaded in " . self::$time ." seconds\n";
    }

}