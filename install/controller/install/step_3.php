<?php

class ControllerInstallStep3 extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('install/step_3');
        $data = $this->language->all();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('install/install');

            $app_env = preg_replace("/[^A-Za-z0-9]/", "", addslashes($this->request->post['app_env']));

            $array_dotenv = [
                'APP_ENV' => $app_env
            ];

            \Copona\Classes\Install::createDotEnv($array_dotenv);

            \Copona\Classes\Install::createDatabaseConfig($app_env, $this->request->post);

            $this->model_install_install->database($this->request->post);

            $this->model_install_install->migration();

            $this->response->redirect($this->url->link('install/step_4'));
        }

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['app_env'])) {
            $data['error_app_env'] = $this->error['app_env'];
        } else {
            $data['error_app_env'] = '';
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

        if (isset($this->request->post['app_env'])) {
            $data['app_env'] = $this->request->post['app_env'];
        } else {
            $data['app_env'] = $this->language->get('placeholder_app_env');
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

    private function validate()
    {
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
            $mysql = @new MySQLi($this->request->post['db_hostname'], $this->request->post['db_username'],
                html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8'),
                $this->request->post['db_database'], $this->request->post['db_port']);

            if ($mysql->connect_error) {
                $this->error['warning'] = $mysql->connect_error;
            } else {
                $mysql->close();
            }
        } elseif ($this->request->post['db_driver'] == 'mpdo') {
            try {
                new \DB\mPDO($this->request->post['db_hostname'], $this->request->post['db_username'],
                    $this->request->post['db_password'], $this->request->post['db_database'],
                    $this->request->post['db_port']);
            } catch (Exception $e) {
                if ($db->connect_errno) {
                    $this->error['warning'] = $db->connect_error;
                }
                $this->error['warning'] = $e->getMessage();
            }
        }

        if (!$this->request->post['username']) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if (!$this->request->post['app_env']) {
            $this->error['app_env'] = $this->language->get('error_app_env');
        }

        if (!$this->request->post['password']) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'],
                FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

}