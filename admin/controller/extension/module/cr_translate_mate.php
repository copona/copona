<?php

//prd();
// quick little helper functions to help format text for html
// call me paranoid about xss
function h($str)
{
    return htmlentities($str, ENT_NOQUOTES, 'UTF-8');
}

// a much simpler version of htmlentities that only converts '<' and '>'
// less secure, but allows users to see accents and stuff instead of html entites
function hs($str)
{
    $search = array('"' => '&quot;', '<' => '&lt;', '>' => '&gt;');
    return str_replace(array('<', '>'), array('&lt;', '&gt;'), mb_convert_encoding($str, 'UTF-8'));
}

function e_cr($str)
{
    echo h($str);
}

class ControllerExtensionModuleCrTranslateMate extends Controller
{
    protected $error = array();
    protected $modName = 'cr_translate_mate'; // the name of this module
    protected $model; // to store the model object for this module

    public function install()
    {
        $this->model()->install();
    }

    public function uninstall()
    {
        $this->model()->uninstall();
    }

    // returns the model object, loading it if it hasn't been loaded yet
    protected function model()
    {
        if (!$this->model) {
            $this->load->model('extension/' . $this->modName);
            $this->model = $this->{'model_extension_' . $this->modName}->getInstance();
        }

        return $this->model;
    }

    public function index()
    {
        // load the module's language file directly into the $data that will be sent to the view
        // (lazy man's way of adding language strings)
        $data = $this->load->language('extension/module/' . $this->modName);
        // add the names of the available languages
        $data['languages'] = $this->model()->langs();

        // handle ajax requests
        if (isset($this->request->request['action']) && $this->request->request['action']) {
            switch ($this->request->request['action']) {
                case 'load': // Retrieve translation texts
                    $this->ajax('load', $data);
                    return;
                case 'save': // Handle form submission
                    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                        $this->ajax('save', $data);
                    }
                    return;
            }
        }

        $this->document->setTitle(h($this->language->get('heading_title')));

        $data['modName'] = $this->modName;


        // add error messages if they exist
        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        // set the list of breadcrumbs to display
        $data['breadcrumbs'] = array(
          array( // Home
            'text' => h($this->language->get('text_home')),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
          ),
          array( // Modules
            'text' => h($data['text_module']),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
          ),
          array( // This module
            'text' => h($data['heading_title']),
            'href' => $this->url->link('extension/module/' . $this->modName, 'token=' . $this->session->data['token'],
              'SSL')
          ),
        );

        // include the file list
        $data['interface'] = isset($_GET['interface']) && $_GET['interface'] == 'admin' ? 'admin' : 'catalog';
        $data['fileSelect'] = $this->model()->fileHTMLSelect($data['interface']);

        // set the from's "action" element to send information back to this controller
        $data['action'] = $this->url->link('extension/module/' . $this->modName,
          'token=' . $this->session->data['token'] . '&action=', 'SSL');

        // set the URL for the "Cancel button" to return user to the modules page
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        // add javascript
        $this->document->addScript('view/javascript/' . $this->modName . '/jquery.stickytableheaders.min.js');
        $this->document->addScript('view/javascript/' . $this->modName . '/' . $this->modName . '.js');
        $this->document->addStyle('view/javascript/' . $this->modName . '/' . $this->modName . '.css');

        // load the common page elements
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // send the data to the view to be output
        $this->response->setOutput($this->load->view('extension/module/' . $this->modName . '.tpl', $data));
    }

    public function ajax($action, $data = array())
    {
        // set error handler to catch all exceptions
        $this->setErrorHandler(true);

        try {
            return $this->{'ajax' . ucfirst($action)}($data);
        } catch (Exception $e) { // if there's a PHP error, return status 500 with the error
            echo $e;
        }
    }

    // custom error handling - includes setting delivery status for ajax
    protected function setErrorHandler($ajax = false)
    {

        set_error_handler(function ($errno, $errstr, $errfile, $errline, $ajax) {
            if ($ajax && !headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
            } // set a 500 status if ajax
            // call Opencart's default error handler
            if (version_compare(VERSION, '2.2.0.0', '<')) { // If version prior to OpenCart 2.2.0.0
                error_handler($errno, $errstr, $errfile, $errline);
            } else {
                $action = new \Copona\System\Engine\Action('startup/error/handler');
                $action->execute($this->registry, array(
                  $errno,
                  $errstr,
                  $errfile,
                  $errline
                ));
            }

            if ($ajax) {
                die();
            } // kill the process if ajax
        });

        if ($ajax) {
            ini_set('display_errors', 1);
        } // we want to ensure errors appear for admin ajax requests (to help find problems)

        register_shutdown_function(function ($ajax) {
            if (is_null($e = error_get_last()) === false) {
                switch ($e['type']) {
                    case E_PARSE :
                        $msg = "PARSE ERROR: ";
                        break;
                    case E_COMPILE_ERROR :
                        $msg = "COMPILE ERROR: ";
                        break;
                    default :
                        $msg = 'FATAL ERROR: ';
                        break;
                }
                $msg .= $e['message'] . ' in ' . $e['file'] . ' on line ' . $e['line'];
                if ($ajax && !headers_sent()) { // set a 500 status for these errors as well
                    header('HTTP/1.1 500 Internal Server Error');
                }

                // save to Opencart's log (since it doesn't do this natively for some reason...)
                $this->log->write($msg);

                die();
            }
        }, $ajax);
    }

    public function ajaxLoad($data)
    {
        if (!$this->validate('load')) {
            $this->returnAjaxErrors($this->error);
        }

        $opts = array();
        if (!empty($_GET['length'])) {
            $opts['length'] = (int)$_GET['length'];
        }
        if (!empty($_GET['startAfter'])) {
            $opts['startAfter'] = (string)$_GET['startAfter'];
        }
        if (!empty($_GET['singleFile'])) {
            $opts['singleFile'] = $_GET['singleFile'] === '' ? false : (string)$_GET['singleFile'];
        }
        if (!empty($_GET['keyFilter'])) {
            $opts['keyFilter'] = $_GET['keyFilter'] === '' ? false : (string)$_GET['keyFilter'];
        }
        if (!empty($_GET['textFilter'])) {
            $opts['textFilter'] = $_GET['textFilter'] === '' ? false : (string)$_GET['textFilter'];
        }
        if (!empty($_GET['dirKey'])) {
            $opts['dirKey'] = (string)$_GET['dirKey'];
        }
        if (!empty($_GET['notTranslated'])) {
            $opts['notTranslated'] = $_GET['notTranslated'] == 'true';
        }

        $data['texts'] = $this->model()->loadTexts($opts);

        // send html output for the javascript to add to the translation table
        $results = array();
        $results['html'] = $this->load->view('extension/module/' . $this->modName . '_table.tpl', $data);
        $results['lastFile'] = $this->model()->getLastLoadedFile();

        echo json_encode($results);
    }

    public function ajaxSave($data)
    {
        if (!$this->validate('save')) {
            $this->returnAjaxErrors($this->error);
        }
        $result = $this->model()->saveTranslation($_POST);
        if (!is_array($result) || !isset($result['success'])) {
            $this->returnAjaxErrors(array($result));
        } else {
            echo json_encode($result);
        }
    }

    // I shy away from adding HTML in controllers, but for these ajax responses
    // I've made an exception due to ease, convenience, and because I'm lazy
    protected function returnAjaxErrors($errors)
    {
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        if (!is_array($errors)) {
            ?>
          <div class="alert alert-danger" role="alert"><?php print_r($errors); ?></div>
            <?php
        } else {
            foreach ($errors as $error) {
                var_dump($error);
                die();
                ?>
              <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                <?php
            }
        }
        die();
    }

    protected function userHasPermission($permission)
    {
        return $this->user->hasPermission($permission, 'extension/module/' . $this->modName);
    }

    // Form validation
    protected function validate($action = null)
    {
        if ($action == 'load') {
            // Check if this user has permission to access this module's information
            if (!$this->userHasPermission('access')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        } else {
            if ($action == 'save') {
                // Check if this user has permission to modify this module
                if (!$this->userHasPermission('modify')) {
                    $this->error['warning'] = $this->language->get('error_permission');
                }
                // Check if the request was made via POST
                if ($this->request->server['REQUEST_METHOD'] != 'POST') {
                    $this->error['post'] = $this->language->get('error_post');
                }
                // Check that all the required fields are present
                $missing = array(); // to hold any missing required fields
                foreach (array('page', 'key', 'lang', 'translation', 'dirKey') as $required) {
                    if (!isset($_POST[$required])) {
                        $missing[] = $required;
                    }
                }
                if (!empty($missing)) {
                    $this->error['missing'] = $this->language->get('error_missing') . ' ' . join(', ', $missing);
                }
            }
        }

        return !$this->error;
    }

}