<?php
class Log {
    private $handle;

    public function __construct($filename) {
        if (!is_dir(DIR_LOGS)) {
            mkdir(DIR_LOGS, \Config::get('directory_permission', 0777), true);
        }

        $this->handle = @fopen(DIR_LOGS . $filename, 'a') ?: null;
    }

    public function write($message) {
        if ($this->handle) {
            fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . " in "
                . debug_backtrace()[0]['file'] . " on line " . debug_backtrace()[0]['line'] . "\n");
        }
    }

    public function __destruct() {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

}