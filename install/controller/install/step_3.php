<?php
class ControllerInstallStep3 extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('install/step_3');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('install/install');

            $http_server = 'http://' . $_SERVER['HTTP_HOST'] . '/';
            $https_server = 'https://' . $_SERVER['HTTP_HOST'] . '/';

			$output = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . $https_server . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/storage/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'system/storage/download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/storage/logs/\');' . "\n";
			$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_OPENCART . 'system/storage/modification/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', \'' . DIR_OPENCART . 'system/storage/upload/\');' . "\n\n";

			$output .= '// CACHE' . "\n";
			$output .= 'define(\'CACHE_EXPIRE\', 3600);' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->post['db_port']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n\n";

            $output .= '// DEBUGGING' . "\n";
            $output .= '// Set to \'debug\' to enable query logging; use with extreme caution' . "\n";
            $output .= '// This logs all queries to the directory specified in DIR_LOGS.' . "\n";
            $output .= '// This directory should NOT be readable by the world!' . "\n";
            $output .= 'define(\'MODE\', \'production\');' . "\n";

			if (!file_exists(DIR_OPENCART . 'config.php')) {
				touch(DIR_OPENCART . 'config.php');
			}

			$file = fopen(DIR_OPENCART . 'config.php', 'w');

			fwrite($file, $output);

			fclose($file);

			$output = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . $http_server . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . $http_server . '\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . $https_server . 'admin/\');' . "\n";
			$output .= 'define(\'HTTPS_CATALOG\', \'' . $https_server . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'admin/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'admin/view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/storage/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'system/storage/download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/storage/logs/\');' . "\n";
			$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_OPENCART . 'system/storage/modification/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', \'' . DIR_OPENCART . 'system/storage/upload/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', \'' . DIR_OPENCART . 'catalog/\');' . "\n\n";

			$output .= '// CACHE' . "\n";
			$output .= 'define(\'CACHE_EXPIRE\', 3600);' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->post['db_port']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n\n";

            $output .= '// DEBUGGING' . "\n";
            $output .= '// Set to \'debug\' to enable query logging; use with extreme caution' . "\n";
            $output .= '// This logs all queries to the directory specified in DIR_LOGS.' . "\n";
            $output .= '// This directory should NOT be readable by the world!' . "\n";
            $output .= 'define(\'MODE\', \'production\');' . "\n";

			if (!file_exists(DIR_OPENCART . 'admin/config.php')) {
				touch(DIR_OPENCART . 'admin/config.php');
			}

			$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');

			fwrite($file, $output);

			fclose($file);

            /* create .htaccess file */

            /* check if mod_rewrite is enabled - checking phpinfo rather than apache_get_modules for greater compatibility */

            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_end_clean();

            $rewrite = strpos($phpinfo, "mod_rewrite");

            if(strstr(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache') && $rewrite && !file_exists(DIR_OPENCART . '.htaccess')) {


                $output = "# 1.To use URL Alias you need to be running apache with mod_rewrite enabled. \n\n";

                $output .= "# 2. In your opencart directory rename htaccess.txt to .htaccess. \n\n";

                $output .= "# For any support issues please visit: http://www.copona.org \n\n";

                $output .= "Options +FollowSymlinks \n\n";

                $output .= "# Prevent Directoy listing \n";
                $output .= "Options -Indexes \n\n";

                $output .= "# Prevent Direct Access to files \n";
                $output .= "<FilesMatch \"(?i)((\.tpl|\.ini|\.log|(?<!robots)\.txt))\"> \n";
                $output .= " Require all denied \n";
                $output .= "## For apache 2.2 and older, replace \"Require all denied\" with these two lines : \n";
                $output .= "# Order deny,allow \n";
                $output .= "# Deny from all \n";
                $output .= "</FilesMatch> \n\n";

                $output .= "# SEO URL Settings \n";
                $output .= "RewriteEngine On \n";
                $output .= "# If your Copona installation does not run on the main web folder make sure you folder it does run in ie. / becomes /shop/ \n\n";

                $output .= "RewriteBase / \n";
                $output .= "RewriteRule ^sitemap.xml$ index.php?route=extension/feed/google_sitemap [L] \n";
                $output .= "RewriteRule ^googlebase.xml$ index.php?route=extension/feed/google_base [L] \n";
                $output .= "RewriteRule ^system/download/(.*) index.php?route=error/not_found [L] \n";
                $output .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
                $output .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
                $output .= "RewriteCond %{REQUEST_URI} !.*\.(ico|gif|jpg|jpeg|png|js|css) \n";
                $output .= "RewriteRule ^([^?]*) index.php?_route_=$1 [L,QSA] \n\n";

                $output .= "### Additional Settings that may need to be enabled for some servers \n";
                $output .= "### Uncomment the commands by removing the # sign in front of it. \n";
                $output .= "### If you get an \"Internal Server Error 500\" after enabling any of the following settings, restore the # as this means your host doesn't allow that. \n\n";

                $output .= "# 1. If your cart only allows you to add one item at a time, it is possible register_globals is on. This may work to disable it: \n";
                $output .= "# php_flag register_globals off \n\n";

                $output .= "# 2. If your cart has magic quotes enabled, This may work to disable it: \n";
                $output .= "# php_flag magic_quotes_gpc Off \n\n";

                $output .= "# 3. Set max upload file size. Most hosts will limit this and not allow it to be overridden but you can try \n";
                $output .= "# php_value upload_max_filesize 999M \n\n";

                $output .= "# 4. set max post size. uncomment this line if you have a lot of product options or are getting errors where forms are not saving all fields \n";
                $output .= "# php_value post_max_size 999M \n\n";

                $output .= "# 5. set max time script can take. uncomment this line if you have a lot of product options or are getting errors where forms are not saving all fields \n";
                $output .= "# php_value max_execution_time 200  \n\n";

                $output .= "# 6. set max time for input to be recieved. Uncomment this line if you have a lot of product options or are getting errors where forms are not saving all fields \n";
                $output .= "# php_value max_input_time 200 \n\n";

                $output .= "# 7. disable open_basedir limitations \n";
                $output .= "# php_admin_value open_basedir none";

                if (!file_exists(DIR_OPENCART . '.htaccess')) {
                    touch(DIR_OPENCART . '.htaccess');
                }

                $file = fopen(DIR_OPENCART . '.htaccess', 'w');

                fwrite($file, $output);

                fclose($file);
            }

            $this->model_install_install->database($this->request->post);

            $this->response->redirect($this->url->link('install/step_4'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_step_3'] = $this->language->get('text_step_3');
		$data['text_db_connection'] = $this->language->get('text_db_connection');
		$data['text_db_administration'] = $this->language->get('text_db_administration');
		$data['text_mysqli'] = $this->language->get('text_mysqli');
		$data['text_mpdo'] = $this->language->get('text_mpdo');
		$data['text_pgsql'] = $this->language->get('text_pgsql');

		$data['entry_db_driver'] = $this->language->get('entry_db_driver');
		$data['entry_db_hostname'] = $this->language->get('entry_db_hostname');
        $data['entry_db_username'] = $this->language->get('entry_db_username');
        $data['entry_db_password'] = $this->language->get('entry_db_password');
        $data['entry_db_database'] = $this->language->get('entry_db_database');
        $data['entry_db_port'] = $this->language->get('entry_db_port');
		$data['entry_db_prefix'] = $this->language->get('entry_db_prefix');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_email'] = $this->language->get('entry_email');

        $data['placeholder_db_hostname'] = $this->language->get('placeholder_db_hostname');
        $data['placeholder_db_username'] = $this->language->get('placeholder_db_username');
        $data['placeholder_db_database'] = $this->language->get('placeholder_db_database');
        $data['placeholder_db_password'] = $this->language->get('placeholder_db_password');
        $data['placeholder_db_port'] = $this->language->get('placeholder_db_port');
        $data['placeholder_db_prefix'] = $this->language->get('placeholder_db_prefix');
        $data['placeholder_username'] = $this->language->get('placeholder_username');
        $data['placeholder_password'] = $this->language->get('placeholder_password');
        $data['placeholder_email'] = $this->language->get('placeholder_email');




		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['db_hostname'])) {
			$data['error_db_hostname'] = $this->error['db_hostname'];
		} else {
			$data['error_db_hostname'] = '';
		}

		if (isset($this->error['db_username'])) {
			$data['error_db_username'] = $this->error['db_username'];
		} else {
			$data['error_db_username'] = '';
		}

		if (isset($this->error['db_database'])) {
			$data['error_db_database'] = $this->error['db_database'];
		} else {
			$data['error_db_database'] = '';
		}

		if (isset($this->error['db_port'])) {
			$data['error_db_port'] = $this->error['db_port'];
		} else {
			$data['error_db_port'] = '';
		}

		if (isset($this->error['db_prefix'])) {
			$data['error_db_prefix'] = $this->error['db_prefix'];
		} else {
			$data['error_db_prefix'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		$data['action'] = $this->url->link('install/step_3');

		if (isset($this->request->post['db_driver'])) {
			$data['db_driver'] = $this->request->post['db_driver'];
		} else {
			$data['db_driver'] = '';
		}

		if (isset($this->request->post['db_hostname'])) {
			$data['db_hostname'] = $this->request->post['db_hostname'];
		} else {
			$data['db_hostname'] = '127.0.0.1';
		}

		if (isset($this->request->post['db_username'])) {
			$data['db_username'] = $this->request->post['db_username'];
		} else {
			$data['db_username'] = 'copona';
		}

		if (isset($this->request->post['db_password'])) {
			$data['db_password'] = $this->request->post['db_password'];
		} else {
			$data['db_password'] = '';
		}

		if (isset($this->request->post['db_database'])) {
			$data['db_database'] = $this->request->post['db_database'];
		} else {
			$data['db_database'] = '';
		}

		if (isset($this->request->post['db_port'])) {
			$data['db_port'] = $this->request->post['db_port'];
		} else {
			$data['db_port'] = 3306;
		}

		if (isset($this->request->post['db_prefix'])) {
			$data['db_prefix'] = $this->request->post['db_prefix'];
		} else {
			$data['db_prefix'] = 'cp_';
		}

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = 'admin';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['mysqli'] = extension_loaded('mysqli');
		$data['pdo'] = extension_loaded('pdo');
		$data['pgsql'] = extension_loaded('pgsql');

		$data['back'] = $this->url->link('install/step_2');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('install/step_3', $data));
	}

	private function validate() {
		if (!$this->request->post['db_hostname']) {
			$this->error['db_hostname'] = $this->language->get('error_db_hostname');
		}

		if (!$this->request->post['db_username']) {
			$this->error['db_username'] = $this->language->get('error_db_username');
		}

		if (!$this->request->post['db_database']) {
			$this->error['db_database'] = $this->language->get('error_db_database');
		}

		if (!$this->request->post['db_port']) {
			$this->error['db_port'] = $this->language->get('error_db_port');
		}

		if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
			$this->error['db_prefix'] = $this->language->get('error_db_prefix');
		}

		if ($this->request->post['db_driver'] == 'mysqli') {
			$mysql = @new MySQLi($this->request->post['db_hostname'], $this->request->post['db_username'], html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8'), $this->request->post['db_database'], $this->request->post['db_port']);

			if ($mysql->connect_error) {
				$this->error['warning'] = $mysql->connect_error;
			} else {
				$mysql->close();
			}
		} elseif ($this->request->post['db_driver'] == 'mpdo') {
			try {
				new \DB\mPDO($this->request->post['db_hostname'], $this->request->post['db_username'], $this->request->post['db_password'], $this->request->post['db_database'], $this->request->post['db_port']);
			} catch (Exception $e) {
				$this->error['warning'] = $e->getMessage();
			}
		}

		if (!$this->request->post['username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		/* TODO:
		  We have already checked the availabliliity of file. This is useless.
		  if (!is_writable(DIR_OPENCART . 'config.php') || ( file_exists() ) {
		  $this->error['warning'] = $this->language->get('error_config') . DIR_OPENCART . 'config.php!';
		  }

		  if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
		  $this->error['warning'] = $this->language->get('error_config') . DIR_OPENCART . 'admin/config.php!';
		  }
		 */

		return !$this->error;
	}

}