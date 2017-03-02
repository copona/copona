<?php
class Request {
    public $get = array();
    public $post = array();
    public $cookie = array();
    public $files = array();
    public $server = array();

    public function __construct() {
        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);
        $this->request = $this->clean($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
        $this->server = $this->clean($_SERVER);
    }
    
    public function paramsToStr( $entries, $from = "get" ){
        if( is_string( $entries ) ){
            
            $entries = preg_replace("#[\/\;\,]#", " ", $entries);
            $entries = explode(" ", $entries);
            
            if( is_string( $entries ) )
                $entries = array( $entries );
        }
        
        $str = "";
        $method = $from ."ParamToStr";
        foreach( $entries as $entry ){
            
            if( $from == "both" ){
                
                $str .= $this->getParamToStr( $entry );
                $str .= $this->postParamToStr( $entry );
                
            }else $str .= $this->$method( $entry );
        }
        return $str;
    }
    
    public function getParamToStr( $entry ){
        if ( isset( $this->get[ $entry ] ) ) {
            return '&'. $entry .'='. $this->get[ $entry ];
        }else return "";
    }
    
    public function postParamToStr( $entry ){
        if( isset( $this->post[ $entry ] ) ) {
            return '&'. $entry .'='. $this->post[ $entry ];
        }else return "";
    }
    
    public function params( $entries, $from = "get" ) {
        if( is_string( $entries ) ){
            
            $entries = preg_replace("#[\/\;\,]#", " ", $entries);
            $entries = explode(" ", $entries);
            
            if( is_string( $entries ) )
                $entries = array( $entries );
        }
        $data = array();
        $method = $from ."Param";
        foreach( $entries as $entry ){
            
            if( $from == "both" ){
                
                $data[ $entry ] = $this->getParam( $entry );
                if( empty( $data[ $entry ] ) )
                    $data[ $entry ] = $this->postParam( $entry );
                
            }else $data[ $entry ] = $this->$method( $entry );
            
        }
		return $data;
    }
    
    public function getParam( $entry ){
        if ( isset( $this->get[ $entry ] ) ) {
            return $this->get[ $entry ];
        }
    }
    
    public function postParam( $entry ){
        if( isset( $this->post[ $entry ] ) ) {
            return $this->post[ $entry ];
        }
    }

    public function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }

}
