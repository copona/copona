<?php
// author wazzzar

class queryParams {
	protected $data;

	public function __construct( $data ){
		
		$this->data = $data;
		
	}
	
	public function toStr( $entries ){
		if( is_string( $entries ) ){
			
			$entries = preg_replace("#[\/\;\,]#", " ", $entries);
			$entries = explode(" ", $entries);
			
			if( is_string( $entries ) )
				$entries = array( $entries );
		}
		
		$str = "";
		
		foreach( $entries as $entry ){
			$entry = (string)$entry;
			
			if ( isset( $this->data[ $entry ] ) ) {
				$str .= '&'. $entry .'='. $this->data[ $entry ];
			}
			
		}
		
		return $str;
		
	}

}

/*

// before queryParams object
$url = '';

if (isset($this->request->get['sort'])) {
	$url .= '&sort=' . $this->request->get['sort'];
}

if (isset($this->request->get['order'])) {
	$url .= '&order=' . $this->request->get['order'];
}

if (isset($this->request->get['page'])) {
	$url .= '&page=' . $this->request->get['page'];
}

if (isset($this->request->get['limit'])) {
	$url .= '&limit=' . $this->request->get['limit'];
}

// using queryParams object
$get = new queryParams( $this->request->get );
$url = $get->toStr('sort,order/page;limit other'); // available string delimiters: ,;/ [space]
// or 
$url = $get->toStr( array( 'sort','order','page','limit' ) );

*/
