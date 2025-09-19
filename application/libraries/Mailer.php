<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class Mailer {
     public function send(string $to_email, 
                        string $to_name, 
                        string $subject = '', 
                        string $body = '',
                        string $alt_body = '', 
                        array $attachments = []): bool
    {
        $mail = new PHPMailer();
        try{
            //Server setting
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;          //Enable verbose debug
            $mail->isSMTP();                                //Send using SMTP
            $mail->Host = $_ENV['SMTP_HOST'];                 //Set the sender 
            $mail->SMTPAuth = true;                         //Enable SMTP auth
            $mail->Username = $_ENV['SMTP_NAME'];    //SMTP username
            $mail->Password = $_ENV['SMTP_PASS'];        //Account password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//Enable STARTTLS encryption
            $mail->Port = $_ENV['SMTP_PORT'];                              //TCP port

            //Recipients
            $mail->setFrom($_ENV['SMTP_NAME'],'Mailer');
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
            $mail->Body    = $body ;
            $mail->AltBody = $alt_body;
            
            //Send mail
            $mail->send();  
            return true;
        }
        catch (Exception $e) {
            log_message('mailer','Failed to send: '.$e->getMessage());
            return false;
        }
    }
}