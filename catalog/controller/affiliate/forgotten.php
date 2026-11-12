<?php
class ControllerAffiliateForgotten extends Controller {
    private $error = array();

    public function index() {
        if ($this->affiliate->isLogged()) {
            $this->response->redirect($this->url->link('affiliate/account', '', true));
        }

        $this->load->language('affiliate/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('affiliate/affiliate');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Same response whether or not the address is registered, so the form
            // can't be used to find out who is an affiliate (#206). The password is
            // no longer replaced and e-mailed: the affiliate gets a reset link.
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->request->post['email']);

            if ($affiliate_info && $affiliate_info['approved']) {
                $this->load->language('mail/forgotten');

                $code = token(40);

                $this->model_affiliate_affiliate->editResetCode($affiliate_info['email'], $code);

                $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

                $message = sprintf($this->language->get('text_greeting_affiliate'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
                $message .= $this->language->get('text_change') . "\n\n";
                $message .= $this->url->link('affiliate/reset', 'code=' . $code, true) . "\n\n";
                $message .= sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']) . "\n\n";

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                $mail->setTo($affiliate_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();

                // Add to activity log
                if ($this->config->get('config_customer_activity')) {
                    $this->load->model('affiliate/activity');

                    $activity_data = array(
                        'affiliate_id' => $affiliate_info['affiliate_id'],
                        'name'         => $affiliate_info['firstname'] . ' ' . $affiliate_info['lastname']
                    );

                    $this->model_affiliate_activity->addActivity('forgotten', $activity_data);
                }
            }

            $this->load->language('affiliate/forgotten');

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('affiliate/login', '', true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_forgotten'),
            'href' => $this->url->link('affiliate/forgotten', '', true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_email'] = $this->language->get('text_email');

        $data['entry_email'] = $this->language->get('entry_email');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('affiliate/forgotten', '', true);

        $data['back'] = $this->url->link('affiliate/login', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('affiliate/forgotten', $data));
    }

    protected function validate() {
        // Only the format is checked: whether the address is registered must not show (#206).
        if (empty($this->request->post['email']) || !is_string($this->request->post['email']) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

}