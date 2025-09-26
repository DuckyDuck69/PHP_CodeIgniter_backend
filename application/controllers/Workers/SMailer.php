<?php
//Go forever until stopped
ini_set('max_execution_time', 0);

use Pheanstalk\Pheanstalk;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * @property Mailer $mailer
 */
/**
* RUN (Windows):
*   "C:\ST_Group\xaamp81\php\php.exe" index.php workers smailer run
 */
class SMailer extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library("mailer");
        $this->load->library("queue");
        $this->load->helper("input_check");
        if (php_sapi_name() !== 'cli') show_404(); //block web access 
    }

    public function run(){
        echo "Email worker started. Watching tube: emails\n";

        $tube = 'smailer';
        $host = '127.0.0.1';
        $port = 11300;

        $queue = new Pheanstalk($host, $port);
        // Watch only the smailer tube
        $queue->watch($tube)->ignore('default');

        while(true){

            //Ask Beanstalk for a job, wait up to 5 sec
            $job = $queue->reserve(5); 
            
            //Loop again if no job found
            if(!$job){ 
                log_message('debug','No job found');
                continue; 
            } 
            $p = json_decode($job->getData(), true); //decode to array

            $to_email   = check_input($p['to_email']);
            $to_name    = check_input($p['to_name']);
            $subject    = check_input($p['subject']);
            $body_html    = check_input($p['body_html']);
            $alt_body   = check_input($p['alt_body']); 
            $attachments= check_input($p['attachments']);

            try{
                //Try to send the mail 
                $ok = $this->mailer->send($to_email, $to_name, $subject, $body_html, $alt_body, $attachments);

                if (!$ok) {
                    throw new \RuntimeException('Mailer->send() returned false');
                }
                //If success => tell Beanstalkd to delete the job 
                $queue->delete($job);
                echo "Sent {$subject} to {$to_email}\n";

            }catch(Exception $e){
                $queue->delete($job);
                //Try again with 30 seconds pause
                $queue->putInTube('smailer', $job->getData(), 1024, 30, 60);
                echo "Retry in 30s: {$e->getMessage()}\n";
            }
        }   
    }

}