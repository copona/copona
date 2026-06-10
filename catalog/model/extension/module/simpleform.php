<?php
class ModelExtensionModuleSimpleform extends Model {

    public function saveFormData($data) {


        //TODO: move to admin?
        // installation
        $res = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "simpleform'");

        if (!(boolean)$res->num_rows) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "simpleform` (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				`name` varchar(254) NOT NULL,
				`surname` varchar(254) NOT NULL,
				`phone` varchar(254) NOT NULL,
				`address` varchar(254) NOT NULL,
				`email` varchar(254) NOT NULL,
				`month` varchar(254) NULL,
				`form_id` varchar(254) NULL,
				`regnumber` varchar(254) NULL,
				`link` varchar(254) NULL,
				`date` TIMESTAMP ) CHARSET=utf8";
            $this->db->query($sql);
        }


        $sql = "INSERT INTO " . DB_PREFIX . "simpleform
        SET
        name = '" . $this->db->escape(isset($data['name']) ? $data['name'] : '') . "',
        surname = '" . $this->db->escape(isset($data['surname']) ? $data['surname'] : '') . "',
        phone = '" . $this->db->escape(isset($data['phone']) ? $data['phone'] : '') . "',
        address = '" . $this->db->escape(isset($data['address']) ? $data['address'] : '') . "',
        email = '" . $this->db->escape(isset($data['email']) ? $data['email'] : '') . "',
        month = '" . $this->db->escape(isset($data['month']) ? $data['month'] : '') . "',
        form_id = '" . $this->db->escape(isset($data['form_id']) ? $data['form_id'] : '') . "',
        link = '" . $this->db->escape(isset($data['link']) ? $data['link'] : '' ) . "',
        regnumber = '" . $this->db->escape(isset($data['regnumber']) ? $data['regnumber'] : '') . "'";
        //prd($sql);


        $result = $this->db->query($sql);

        if ($result) {
            $subject = "Simple webform email";
            $message = "\nNew request from website!\n\n\n";
            $message .= "Name: " . $data['name'] . "\n";
            $message .= "Surname: " . $data['surname'] . "\n";
            $message .= "Phone: " . $data['phone'] . "\n";
            $message .= "Email: " . $data['email'] . "\n";
            $message .= "regnumber: " . (isset($data['regnumber']) ? $data['regnumber'] : '') . "\n";
            $message .= "address: " . (isset($data['address']) ? $data['address'] : '') . "\n";
            $message .= "month: " . (isset($data['month']) ? $data['month'] : '') . "\n";
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender("Porsche Web site");
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $emails = explode(',', $this->config->get('config_mail_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
        //prd( $data  );
        return $result;
    }

}