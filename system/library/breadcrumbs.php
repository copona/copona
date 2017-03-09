<?php
// author wazzzar

class Breadcrumbs {
    private $path = array();
    protected $language;
    protected $url;
	
    public function __construct( $registry ){
	
        $this->language = $registry->get('language');
        $this->url = $registry->get('url');
        $this->push( 'text_home', 'common/home' );
    }
	
    public function push( $text, $route, $args = "", $secure = false ){
	
        $this->path[] = array(
            'text' => $this->language->get( $text ),
            'href' => $this->url->link( $route, $args, $secure )
        );
    }

    public function render() {
        // if in path only home link
        if (count($this->path) == 1)
            return null;

        $html = '<ul class="breadcrumb">';
        foreach ($this->path as $part) {
            $html .= '<li><a href="' . $part['href'] . '">' . $part['text'] . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function streem() {

        echo $this->render();
    }

    public function getPath() {
        // if in path only home link
        if (count($this->path) == 1)
            return null;

        return $this->path;
    }

}
