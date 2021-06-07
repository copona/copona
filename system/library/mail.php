<?php

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    protected $to;
    protected $from;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $attachments = [];
    protected $log;
    protected $sentMIMEMessage;
    public $protocol = 'mail';
    public $smtp_hostname;
    public $smtp_username;
    public $smtp_password;
    public $smtp_port = 25;
    public $smtp_timeout = 5;
    public $verp = false;
    public $parameter = '';
    public $ErrorInfo = '';

    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        $this->log = new Log('maillog.log');
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function addAttachment($filename)
    {
        $this->attachments[] = $filename;
    }

    public function getSentMIMEMessage()
    {
        return $this->sentMIMEMessage;
    }

    public function send()
    {
        if (!$this->to) {
            throw new \Exception('Error: E-Mail to required!');
        }

        if (!$this->from) {
            throw new \Exception('Error: E-Mail from required!');
        }

        if (!$this->sender) {
            throw new \Exception('Error: E-Mail sender required!');
        }

        if (!$this->subject) {
            throw new \Exception('Error: E-Mail subject required!');
        }

        if ((!$this->text) && (!$this->html)) {
            throw new \Exception('Error: E-Mail message required!');
        }

        if (is_array($this->to)) {
            $to = implode(',', $this->to);
        } else {
            $to = $this->to;
        }

        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $this->log->write('ERROR: PHP Mailer class required! Install using Composer1!');
            return false;
        }
        try {


            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';

            if ($this->protocol == 'smtp') {


                $mail->Timeout = $this->smtp_timeout;

                if (substr($this->smtp_hostname, 0, 3) == 'tls') {
                    $hostname = substr($this->smtp_hostname, 6);
                } else {
                    $hostname = $this->smtp_hostname;
                }

                $this->log->write($hostname);

                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = $hostname;  // Specify main and backup SMTP servers
                $mail->Helo = getenv('SERVER_NAME');
                $mail->Hostname = getenv('SERVER_NAME');


                if (!empty($this->smtp_username) && !empty($this->smtp_password)) {
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = $this->smtp_username;                 // SMTP username
                    $mail->Password = $this->smtp_password;                           // SMTP password
                }

                if (substr($this->smtp_hostname, 0, 3) == 'tls') {
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                }
                $mail->Port = $this->smtp_port;                                    // TCP port to connect to
            }

            $mail->setFrom($this->from, $this->sender);

            if (!is_array($to)) {
                $mail->addAddress((string)$this->to);     // Add a recipient
            } else {
                foreach ($to as $addy) {
                    $mail->addAddress((string)$addy);     // Add a recipient
                }
            }

            if (!$this->reply_to) {
                $mail->addReplyTo($this->from);
            } else {
                $mail->addReplyTo($this->reply_to);
            }

            foreach ($this->attachments as $attachment) {
                if (file_exists($attachment)) {
                    $mail->addAttachment($attachment);         // Add attachments
                }
            }

            if (!$this->html) {
                $mail->isHTML(false);
                $mail->Body = $this->text . PHP_EOL;
            } else {
                $mail->isHTML(true);

                if ($this->text) {
                    $altmessage = $this->text . PHP_EOL;
                } else {
                    $altmessage = 'This is a HTML email and your email client software does not support HTML email!' . PHP_EOL;
                }

                $mail->Body = $this->html . PHP_EOL;
                $mail->AltBody = $altmessage;
            }

            $mail->Subject = $this->subject;

            if (!$mail->send()) {
                echo "<h4>Mailer Error:</h4><p>" . $mail->ErrorInfo . ".<br><br>";
                echo "Please check your mail configuration.</p>";

                $this->ErrorInfo = $mail->ErrorInfo;

                $this->log->write("ERROR: From: $this->from To: $this->to Subject: $this->subject");
                // $this->log->write( "ERROR: ******************* HTML ******************* " );
                // $this->log->write( "ERROR: $this->html" );
                // $this->log->write( "ERROR: ******************* TEXT ******************* " );
                // $this->log->write( "ERROR: $this->text" );
                // $this->log->write( "ERROR: ******************* ERROR ******************* " );
                $this->log->write("ERROR: " . $mail->ErrorInfo);
                return false;
            } else {
                $this->sentMIMEMessage = $mail->getSentMIMEMessage();
                $this->log->write("SUCCESS: From: $this->from To: $this->to Subject: $this->subject");
                return true;
            }
        } catch (phpmailerException $e) {
            throw new \Exception('Mail Configuration Error: ' . $e->errorMessage());
        } catch (Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }

}
