<?php
class ControllerStartupDemo extends Controller {

    // Demo mode: the admin is browsable but nothing can be changed.
    // Write actions that check the 'modify' permission are refused by
    // Cart\User::hasPermission(); this also refuses every POST except the
    // login form, so actions without a permission check (own password,
    // password reset, file manager) can't change anything either.
    public function index() {
        if (!$this->config->get('demo.mode') || $this->request->server['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

        if ($route == 'common/login') {
            return;
        }

        $this->load->language('common/demo');

        $message = $this->language->get('error_demo');

        if (!empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => array('warning' => $message), 'warning' => $message)));
            $this->response->output();
            exit;
        }

        $this->session->data['demo_warning'] = $message;

        $redirect = isset($this->request->server['HTTP_REFERER']) ? $this->request->server['HTTP_REFERER'] : '';

        if (strpos($redirect, HTTP_SERVER) !== 0 && strpos($redirect, HTTPS_SERVER) !== 0) {
            $redirect = $this->url->link('common/dashboard', isset($this->session->data['token']) ? 'token=' . $this->session->data['token'] : '', true);
        }

        $this->response->redirect($redirect);
    }

}
