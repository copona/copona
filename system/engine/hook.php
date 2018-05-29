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


                // Ability to pass class object and method.
                if( gettype($function) == 'array' && method_exists($function[0], $function[1]) ) {
                    $function[0]->{$function[1]}($data, $this->registry);
                } elseif (is_string($function) && function_exists($function)) {
                    $function($data, $this->registry);
                } else {
                    // pr('Function' . $function . ' does not exist!');
                    // Functions does not exist!
                }
            }
        }
    }

    public function getHooks() {
        return $this->hooks;
    }

}