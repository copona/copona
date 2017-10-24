<?php

class ControllerCommonElfinder extends Controller
{
    /**
     * Show elFinder
     */
    public function index()
    {
        $this->load->language('common/elfinder');

        $data = [];

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/elfinder', 'token='.$this->session->data['token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/elfinder', 'token='.$this->session->data['token'], true),
        ];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['connector_url'] = $this->url->link('common/elfinder/connector', 'token='.$this->session->data['token']);

        // Attach styles
        $this->document->addStyle('/admin/view/assets/jquery-ui/jquery-ui.min.css');
        $this->document->addStyle('/admin/view/assets/elfinder/css/elfinder.min.css');
        $this->document->addStyle('/admin/view/assets/elfinder/css/theme.css');

        // Attach scripts
        $this->document->addScript('/admin/view/assets/jquery-ui/jquery-ui.min.js');
        $this->document->addScript('/admin/view/assets/elfinder/js/elfinder.min.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/elfinder', $data));
    }

    /**
     * elFinder connector
     */
    public function connector()
    {
        $autoload_path = '/library/elfinder/autoload.php';
        $autoload_full_path = DIR_SYSTEM.$autoload_path;

        if (!file_exists($autoload_full_path)) {
            echo 'Error! File '.$autoload_path.' not exists!';
            exit(1);
        }
        require $autoload_full_path;

        $opts = [
            // 'debug' => true,
            'roots' => [
                [
                    'driver' => 'LocalFileSystem',
                    'path' => DIR_IMAGE,
                    'URL' => '../image',
                    'uploadDeny' => ['all'],
                    'uploadAllow' => ['image', 'text/plain'],
                    'uploadOrder' => ['deny', 'allow'],
                    'accessControl' => [$this, 'access'],
                ],
            ],
        ];

        // Run elFinder
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

    /**
     * Simple function to demonstrate how to control file access using "accessControl" callback.
     * This method will disable accessing files/folders starting from '.' (dot)
     *
     * @param  string $attr attribute name (read|write|locked|hidden)
     * @param  string $path file path relative to volume root directory started with directory separator
     *
     * @return bool|null
     **/
    public function access($attr, $path, $data, $volume)
    {
        // if file/folder begins with '.' (dot)
        if (strpos(basename($path), '.') === 0) {
            // set read+write to false, other (locked+hidden) set to true
            return !($attr == 'read' || $attr == 'write');
        }

        // elFinder decide it itself
        return null;
    }
}
