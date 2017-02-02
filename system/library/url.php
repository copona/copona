<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();
	private $code = '';

	public function __construct($url, $ssl = '', $registry) {

		$this->config = $registry->get('config');
		$this->session = $registry->get('session');

		$this->url = $url;
		$this->ssl = $ssl;

		$this->code = ($this->config->get('config_seo_url') ? $this->session->data['language'] : '');
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		$code = $this->code ? $this->code . "/" : '';
		if ($this->ssl && $secure) {
			$url = $this->ssl . $code . 'index.php?route=' . $route;
		} else {
			$url = $this->url . $code . 'index.php?route=' . $route;
		}

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}

}