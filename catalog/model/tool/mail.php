<?php

class ModelToolMail extends Model
{
    /**
     * @author Arnis Juraga <arnis.juraga@gmail.com>
     *
     * @param string $from_email
     * @param string $to_email
     * @param string $subject
     * @param array $data
     * @param string $template Loads template from $template path, or sends plain text, if no template is specified.
     * @param int $store_id
     * @param string $store_name taken from store value, if set.
     */
    public function sendMail($from_email = '',
                             $to_email = '',
                             $subject = '',
                             $data = [],
                             $template = '',
                             $store_id = 0,
                             $store_name = ''
    )
    {


        $this->load->model('setting/setting');
        // $text = $data['message'];
        // to: $order_info['email']
        // store_id: $order_info['store_id']
        // store_name: $order_info['store_name']
        $html_message = '';

        $from_email = !$from_email
            ? $this->model_setting_setting->getSettingValue('config_email', $store_id)
            : $from_email;

        $to_email = !$to_email
            ? $this->model_setting_setting->getSettingValue('config_email', $store_id)
            : $to_email;

        $from_name = !$store_name
            ? $this->model_setting_setting->getSettingValue('config_name', $store_id)
            : $store_name;

        $subject = !$subject
            ? '(subject)'
            : $subject;

        // if template is not "plain", then we load HTML data from template
        // else - format NewLines in Plaintext message to <br />

        if ($template) {
            //prd( $this->load->view( $template , $data) );
            $html_message = $this->load->view($template, $data);
        }

        if (!$from_email) {
            $from_email = $this->model_setting_setting->getSettingValue('config_email', $store_id);
        }
 
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES,
            'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($to_email);
        $mail->setFrom($from_email);
        $mail->setSender(html_entity_decode($from_name, ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));

        if($html_message) {
            $mail->setHtml($html_message);
        }

        $mail->setText($data['message']);
        $mail->send();
    }
}
