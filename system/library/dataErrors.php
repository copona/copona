<?php
// author wazzzar

class dataErrors {
    
    private $data;
    protected $language;
    
    public function __construct( $registry ){
        
        $this->data = array();
        $this->language = $registry->get('language');
        
    }
    
    public function set( $entry, $str ){
        
        $this->data[ $entry ] = $this->language->get( $str );
        
    }
    
    public function get( $entries ){
        $data = array();
        
        if( empty( $entries ) ){
            foreach( $this->data as $key => $value )
                $data[ 'error_'. $key ] = $value;
            
            return $data;
        }
        
        if( is_string( $entries ) ){
            
            $entries = preg_replace("#[\/\;\,]#", " ", $entries);
            $entries = explode(" ", $entries);
            
            if( is_string( $entries ) )
                $entries = array( $entries );
        }
        
        foreach( $entries as $entry ){
            $entry = (string)$entry;
            
            $data[ 'error_'. $entry ] = '';
            if ( isset( $this->data[ $entry ] ) ) {
                
                $data[ 'error_'. $entry ] = $this->data[ $entry ];
            }
        }
        if( count( $data ) == 0 )
            return null;
        
        if( count( $data ) == 1 )
            return $data[ $entry ];
        
        return $data;
    }
    
    public function hasErrors(){
        
        return count( $this->data );
        
    }
    
}
