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
 * @property Create_user $create
 * @property Authenticate_user $auth
 * @property Mailer $mailer
 */

class Forms extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form_input');
        $this->load->library('mailer');
        $this->load->model('user_model', 'user');
        $this->load->model('authenticate_model','auth');
    }

    //Handle the store data process and create user after submitting form 
    public function submit()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
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
        
        //Create a new user profile
        $user_id = $this->user->create_user($user_email, $user_name, $user_age, 
                                    $user_gender, $user_job, $user_longitude, 
                                    $user_latitude);
        
        //Create a unique verify link for that user
        $verify_url = $this->auth->authenticate($user_id);
        $subject = 'ST Group: Xac nhan tai khoan';
        $body = 'Xin chào '.$user_name.'<br><br>Xin vui lòng bấm vào đường link này để xác nhận: '
            . '<a href="'.$verify_url.'">Kích hoạt tài khoản</a>';       
        /*
         * Email submitter
         */

        $this->mailer->send($user_email, $user_name, $subject, $body, '', []);
        //Redirect (PRG) => thank-you page
        redirect('thank_you');
    }
}
