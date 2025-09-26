<?php

use MongoDB\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use MongoDB\BSON\UTCDateTime;

/**
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Loader $load
 * @property Track_model $track
 * @property User_model $user
 * @property Authenticate_model $auth
 * @property Mailer $mailer
 * @property Queue $queue
 */

class Forms extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form_input');
        $this->load->library('mailer');
        $this->load->model('user_model', 'user');
        $this->load->model('authenticate_model','auth');
        //Load the Beanstalk server listening on port 11300
        $this->load->library('queue', ['host' => '127.0.0.1', 'port' => 11300]);

    }

    //Handle the store data process and create user after submitting form 
    public function submit()
    {
        if ((isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') !== 'POST') {
            show_error('Invalid method. Must be POST', 405);
            return;
        }
        
        //Retrieve data from HTML form 
        $user_name = convert_data($_POST['fullname']);
        $user_email = convert_data($_POST['email']);
        $user_age = convert_data($_POST['age']);
        $user_gender = convert_data($_POST['gender']);
        $user_job = convert_data($_POST['job']);
        $user_latitude = isset($_POST['latitude'])  ? (float) $_POST['latitude']  : null;
        $user_longitude = isset($_POST['longitude']) ? (float) $_POST['longitude'] : null;
        log_message('debug', print_r($_POST, true));
        //Create a new user profile
        $user_id = $this->user->create_user($user_email, $user_name, $user_age, 
                                    $user_gender, $user_job, $user_longitude, 
                                    $user_latitude);
        
        //Create a unique verify link for that user
        $verify_url = $this->auth->authenticate($user_id);

        //Create image link 
        $img_url = site_url('image_tracking/track_open').'?id='.urlencode($user_id). '&v='.time();
        log_message('debug', 'URL img: '.$img_url);


        $payload = [
            'to_email'    => $user_email,
            'to_name'     => $user_name,
            'subject'     => 'ST Group: Xac nhan tai khoan',
            'body_html'   =>' <img src="'.$img_url.'" alt="imgg" width="1" height="1" style="!important;">'
                            . 'Xin chào '.$user_name.'<br><br>Vui lòng bấm: '
                            . '<a href="'.$verify_url.'">Kích hoạt tài khoản</a>',
            'alt_body' => '',
            'attachments' => []
        ];

        $jobId = $this->queue->putInTube('smailer', $payload, 100, 0, 10);
        log_message('Debug', 'Id of job: '.$jobId);
        

        redirect('thank_you');
    }
}
