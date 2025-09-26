<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Mailer {

    private function env($key, $default = null) {
        $v = getenv($key);
        if ($v === false || $v === '') {
            if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') $v = $_SERVER[$key];
            elseif (isset($_ENV[$key]) && $_ENV[$key] !== '')   $v = $_ENV[$key];
            else $v = null;
        }
        return ($v === null || $v === '') ? $default : $v;
    }

     public function send( $to_email, 
                         $to_name, 
                         $subject = '', 
                         $body_html = '',
                         $alt_body = '', 
                         $attachments = [])
    {
        $mail = new PHPMailer(true);
        try{
            $host   = $this->env('SMTP_HOST');
            $sender =  $this->env('SMTP_NAME');
            $pass   = $this->env('SMTP_PASS');
            //Server setting
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;          //Enable verbose debug
            $mail->isSMTP();                                //Send using SMTP
            $mail->Host = $host;             //Set the sender 
            $mail->SMTPAuth = true;                         //Enable SMTP auth
            $mail->Username =  $sender;   //SMTP username
            $mail->Password = $pass;        //Account password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//Enable STARTTLS encryption
            $mail->Port = 587;;                              //TCP port

            //Recipients
            $mail->setFrom($sender,'Mailer');
            $mail->addAddress($to_email, $to_name);
            //$mail->addReplyTo(address: '','');
            //$mail->addCC('');

            //Attachments
            for($i = 0; $i < count($attachments); $i++){
                $mail->addAttachment($attachments[$i]);         //Add attachments
            }
            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body_html ;
            $mail->AltBody = $alt_body;
            
            //Send mail
            $ok = $mail->send();  
            if (!$ok) {
                log_message('error', 'Mailer send failed: '.$mail->ErrorInfo);
                return false;
            }
            return true;
        }
        catch (Exception $e) {
            log_message('mailer','Failed to send: '.$e->getMessage());
            return false;
        }
    }
}