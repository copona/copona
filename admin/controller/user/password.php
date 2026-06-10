<?php
class ControllerUserPassword extends Controller {
    private $error = array();

    public function index() {
        // $data = $this->language->load('user/user');
        $this->getForm();
    }

    public function change() {
        if ($this->request->post && $this->validate()) {

        } else {
            $this->response->redirect($this->url->link('user/password', 'token=' . $this->session->data['token']));
        }
    }

    protected function getForm() {
        $data = $this->language->load('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        $data['action'] = $this->url->link('user/password/change', 'token=' . $this->session->data['token']);
        $data['error_warning'] = '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['breadcrumbs'] = array();
        $this->response->setOutput($this->load->view('user/password', $data));
    }

    protected function validate() {
        if ($this->request->post['new_password'] != $this->request->post['new_password_confirm']) {
            $this->error['error_confirm'] = 'Paroles nav vienādas!';
        }

        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE "
            . "user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1' limit 1");

        if (!$user_query->num_rows) {
            return false;
        }

        if (!password_verify($this->request->post['old_password'], $user_query->row['password'])) {
            $this->error['error_old_password'] = 'Vecā parole nav pareiza!';
            return false;
        }

        // Update password
        $password_update = $this->db->query("UPDATE " . DB_PREFIX . "user "
            . "SET password = '" . password_hash($this->request->post['new_password'], PASSWORD_DEFAULT) . "' "
            . "WHERE user_id = '" . $this->session->data['user_id'] . "'");

        return $this->error ? false : true;
    }

}
?>