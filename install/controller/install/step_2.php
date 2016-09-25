<?php
class ControllerInstallStep2 extends Controller {
	private $error = array();

	public function index() {
		//$this->language->load('install/step_2');		
		$data = array_merge($data = array(), $this->language->load('install/step_2'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->response->redirect($this->url->link('install/step_3'));
		}

		$this->document->setTitle($data['heading_title']);

		$data['heading_title'] = $data['heading_title'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('install/step_2');

		$data['php_version'] = phpversion();
		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = array(
			'mysql', 
			'mysqli', 
			'pgsql', 
			'pdo'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] = extension_loaded('gd');
		$data['curl'] = extension_loaded('curl');
		$data['mcrypt_encrypt'] = function_exists('mcrypt_encrypt');
		$data['zlib'] = extension_loaded('zlib');
		$data['zip'] = extension_loaded('zip');
		
		$data['iconv'] = function_exists('iconv');
		$data['mbstring'] = extension_loaded('mbstring');

		$data['config_catalog'] = DIR_OPENCART . 'config.php';
		$data['config_admin'] = DIR_OPENCART . 'admin/config.php';
		
		$data['image'] = DIR_OPENCART . 'image';
		$data['image_cache'] = DIR_OPENCART . 'image/cache';
		$data['image_catalog'] = DIR_OPENCART . 'image/catalog';		
		
		$data['cache'] = DIR_SYSTEM . 'storage/cache';
		$data['logs'] = DIR_SYSTEM . 'storage/logs';
		$data['download'] = DIR_SYSTEM . 'storage/download';
		$data['upload'] = DIR_SYSTEM . 'storage/upload';
		$data['modification'] = DIR_SYSTEM . 'storage/modification';

		$data['back'] = $this->url->link('install/step_1');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('install/step_2', $data));
	}

	private function validate() {
		if (phpversion() < '5.4') {
			$this->error['warning'] = $this->language->get('error_version');
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = $this->language->get('error_file_upload');
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = $this->language->get('error_session');
		}

		$db = array(
			'mysql', 
			'mysqli', 
			'pdo', 
			'pgsql'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$this->error['warning'] = $this->language->get('error_db');
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = $this->language->get('error_gd');
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = $this->language->get('error_curl');
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->error['warning'] = $this->language->get('error_mcrypt');
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = $this->language->get('error_zlib');
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = $this->language->get('error_zip');
		}
		
		if (!function_exists('iconv') && !extension_loaded('mbstring')) {
			$this->error['warning'] = $this->language->get('error_mbstring');
		}
		
		if (!file_exists(DIR_OPENCART . 'config.php')) {
			//$this->error['warning'] = $this->language->get('error_catalog_exist');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = $this->language->get('error_catalog_writable');
		} elseif ( filesize(DIR_OPENCART . 'config.php') > 0 ) {
			$this->error['warning'] = "Application 'config.php' file already exists.";
		}

		if (!file_exists(DIR_OPENCART . 'admin/config.php')) {
			//$this->error['warning'] = $this->language->get('error_admin_exist');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = $this->language->get('error_admin_writable');
		} elseif ( filesize(DIR_OPENCART . 'admin/config.php') > 0 ) {
			$this->error['warning'] = "Application 'admin/config.php' file already exists.";
		}

		if (!is_writable(DIR_OPENCART . 'image')) {
			$this->error['warning'] = $this->language->get('error_image');
		}

		if (!is_writable(DIR_OPENCART . 'image/cache')) {
			$this->error['warning'] = $this->language->get('error_image_cache');
		}

		if (!is_writable(DIR_OPENCART . 'image/catalog')) {
			$this->error['warning'] = $this->language->get('error_image_catalog');
		}
		
		if (!is_writable(DIR_SYSTEM . 'storage/cache')) {
			$this->error['warning'] = $this->language->get('error_cache');
		}

		if (!is_writable(DIR_SYSTEM . 'storage/logs')) {
			$this->error['warning'] = $this->language->get('error_log');
		}

		if (!is_writable(DIR_SYSTEM . 'storage/download')) {
			$this->error['warning'] = $this->language->get('error_download');
		}

		if (!is_writable(DIR_SYSTEM . 'storage/upload')) {
			$this->error['warning'] = $this->language->get('error_upload');
		}

		if (!is_writable(DIR_SYSTEM . 'storage/modification')) {
			$this->error['warning'] = $this->language->get('error_modification');
		}

		return !$this->error;
	}
}
