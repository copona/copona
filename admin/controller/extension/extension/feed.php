<?php
class ControllerExtensionExtensionFeed extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/extension/feed');

        $this->load->model('extension/extension');

        $this->getList();
    }

    public function install() {
        $this->load->language('extension/extension/feed');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->install('feed', $this->request->get['extension']);

            $this->load->model('user/user_group');

            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/feed/' . $this->request->get['extension']);
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/feed/' . $this->request->get['extension']);

            // Call install method if it exsits
            try {
                $this->load->controller('extension/feed/' . $this->request->get['extension'] . '/install');
            } catch (\Copona\Exception\ActionException $e) {

            }

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->getList();
    }

    public function uninstall() {
        $this->load->language('extension/extension/feed');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('feed', $this->request->get['extension']);

            // Call uninstall method if it exsits
            try {
                $this->load->controller('extension/feed/' . $this->request->get['extension'] . '/uninstall');
            } catch (\Copona\Exception\ActionException $e) {

            }

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->getList();
    }

    protected function getList() {
        $data=  $this->load->language('extension/extension/feed');
        $data['heading_title'] = $this->language->get('heading_title');

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

        $extensions = $this->model_extension_extension->getInstalled('feed');

        $data['extensions'] = [];

        // Compatibility code for old extension folders
        $files = glob('{' . DIR_APPLICATION . 'controller/{extension/feed,feed}/*.php,'
                      . $this->config->get('extension.dir') . '/*/*/admin/controller/extension/feed/*.php}', GLOB_BRACE);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('extension/feed/' . $extension);

                $data['extensions'][] = array(
                    'name'      => $this->language->get('heading_title'),
                    'status'    => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'install'   => $this->url->link('extension/extension/feed/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'uninstall' => $this->url->link('extension/extension/feed/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'installed' => in_array($extension, $extensions),
                    'edit'      => $this->url->link('extension/feed/' . $extension, 'token=' . $this->session->data['token'], true)
                );
            }
        }

        $this->response->setOutput($this->load->view('extension/extension/feed', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/extension/feed')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}