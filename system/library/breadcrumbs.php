<?php
// author wazzzar

class Breadcrumbs {

	private $path = array();
	private $registry;
	
	public function __construct( $registry ){
	
		$this->registry = $registry;
		$this->push( 'text_home', 'common/home' );
	
	}
	
	public function push( $text, $route ){
	
		$this->path[] = array(
			'text' => $this->registry->language->get( (string) $text ),
			'href' => $this->registry->url->link( (string) $route )
		);
	
	}
	
	public function render(){
	
		$html = '<ul class="breadcrumb">';
		foreach ( $this->path as $part ) {
			$html .= '<li><a href="' . $part['href'] .'">'. $part['text'] .'</a></li>';
		}
		$html .= '</ul>';
		return $html;
	
	}
	
	public function streem(){
		
		echo $this->render();
		
	}
	
	public function getPath(){
		
		return $this->path;
		
	}

}

/* example

$bread_crumbs = new Breadcrumbs( $this );
$bread_crumbs->push( 'text_account', 'account/account' );
$data['breadcrumbs_html'] = $bread_crumbs->render();
// we have breadcrumbs html

// for compatibility
$data['breadcrumbs'] = $bread_crumbs->getPath();

*/
