<?php
class Hook {
    protected $registry;
    protected $data = array();
    private $hooks;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function setHook($string, $data = [ ]) {
        $this->hooks[$string][] = $data;
    }

    public function getHook($string, &$data = [ ]) {
        if (!empty($this->hooks[$string])) {
            foreach ($this->hooks[$string] as $function) {
                if (function_exists($function)) {
                    $function($data, $this->registry);
                } else {
                    // Functions does not exists!
                }
            }
        }
    }

    public function getHooks() {
        return $this->hooks;
    }

}