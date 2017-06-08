<?php
class ControllerExtensionExtensionShipping extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/extension/shipping');

        $this->load->model('extension/extension');

        $this->getList();
    }

    public function install() {
        $this->load->language('extension/extension/shipping');

        $this->load->model('extension/extension');

        if ($this->validate()) {

//            prd();
            $this->model_extension_extension->install('shipping', $this->request->get['extension']);

            $this->load->model('user/user_group');

            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/shipping/' . $this->request->get['extension']);
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/shipping/' . $this->request->get['extension']);

            // Call install method if it exsits
            $this->load->controller('extension/shipping/' . $this->request->get['extension'] . '/install');

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->getList();
    }

    public function uninstall() {
        $this->load->language('extension/extension/shipping');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('shipping', $this->request->get['extension']);

            // Call uninstall method if it exsits
            $this->load->controller('extension/shipping/' . $this->request->get['extension'] . '/uninstall');

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->getList();
    }

    protected function getList() {
        $data=  $this->load->language('extension/extension/shipping');
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

        $extensions = $this->model_extension_extension->getInstalled('shipping');
        $extensions_dir = preg_replace('/\/[a-z]*\/$/','',DIR_SYSTEM);

        foreach ($extensions as $key => $value) {

            $extension_files = glob($extensions_dir . "/extensions/*/*/admin/controller/extension/shipping/" . $value . ".php");

            if (!is_file(DIR_APPLICATION . 'controller/extension/shipping/' . $value . '.php')
                && !is_file(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')
                && empty($extension_files[0])) {
                $this->model_extension_extension->uninstall('shipping', $value);
                unset($extensions[$key]);
            }
        }

        $data['extensions'] = array();
        $extensions_dir = preg_replace('/\/[a-z]*\/$/','',DIR_SYSTEM);
        // Compatibility code for old extension folders
        $files = glob('{' . DIR_APPLICATION . 'controller/{extension/shipping,shipping}/*.php,'
            . $extensions_dir . '/extensions/*/*/admin/controller/extension/shipping/*.php}', GLOB_BRACE);

        if ($files) {
            foreach ($files as $key => $file) {
                $extension = basename($file, '.php');

                $this->load->language('extension/shipping/' . $extension);

                $data['extensions'][] = array(
                    'name'       => $key+1 . ". " . $this->language->get('heading_title') . " (" . $extension . ") ",
                    'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'sort_order' => $this->config->get($extension . '_sort_order'),
                    'install'    => $this->url->link('extension/extension/shipping/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'uninstall'  => $this->url->link('extension/extension/shipping/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'installed'  => in_array($extension, $extensions),
                    'edit'       => $this->url->link('extension/shipping/' . $extension, 'token=' . $this->session->data['token'], true)
                );
            }
        }

        $this->response->setOutput($this->load->view('extension/extension/shipping', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/extension/shipping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}