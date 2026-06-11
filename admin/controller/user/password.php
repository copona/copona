<?php
class ControllerUserPassword extends Controller {
    private $error = [];

    public function index() {
        $this->getForm();
    }

    public function change() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->load->model('user/user');
            $this->model_user_user->editPassword($this->session->data['user_id'], $this->request->post['new_password']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('user/password', 'token=' . $this->session->data['token']));
        }
        $this->getForm();
    }

    protected function getForm() {
        $this->load->language('user/password');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title']      = $this->language->get('heading_title');
        $data['entry_old_password'] = $this->language->get('entry_old_password');
        $data['entry_new_password'] = $this->language->get('entry_new_password');
        $data['entry_confirm']      = $this->language->get('entry_confirm');
        $data['button_save']        = $this->language->get('button_save');
        $data['button_cancel']      = $this->language->get('button_cancel');

        $data['error_warning']      = isset($this->error['warning'])      ? $this->error['warning']      : '';
        $data['error_old_password'] = isset($this->error['old_password']) ? $this->error['old_password'] : '';
        $data['error_new_password'] = isset($this->error['new_password']) ? $this->error['new_password'] : '';
        $data['error_confirm']      = isset($this->error['confirm'])      ? $this->error['confirm']      : '';

        $data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
        unset($this->session->data['success']);

        $data['action'] = $this->url->link('user/password/change', 'token=' . $this->session->data['token']);
        $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token']);

        $data['breadcrumbs'] = [
            [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token']),
            ],
            [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('user/password', 'token=' . $this->session->data['token']),
            ],
        ];

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/password', $data));
    }

    protected function validate() {
        $this->load->language('user/password');

        if (utf8_strlen($this->request->post['new_password']) < 4 || utf8_strlen($this->request->post['new_password']) > 40) {
            $this->error['new_password'] = $this->language->get('error_new_password');
        }

        if ($this->request->post['new_password'] != $this->request->post['new_password_confirm']) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1' LIMIT 1");

        if (!$user_query->num_rows || !password_verify($this->request->post['old_password'], $user_query->row['password'])) {
            $this->error['old_password'] = $this->language->get('error_old_password');
        }

        return !$this->error;
    }
}
