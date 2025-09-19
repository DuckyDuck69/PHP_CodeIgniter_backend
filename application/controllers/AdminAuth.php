<?php
use MongoDB\Client;

class AdminAuth extends CI_Controller {
    private $collection;
    public function __construct() {
        parent::__construct();
        $this->load->helper(['form_input_helper']);
        $this->load->library('session');
      
        
    }
    public function login() {

        //If it is a GET method, load the admin_login page 
        if ($this->input->method() === 'get') {
            $this->load->view('login');  //Load login page
            return;
        }

        //Check credential
        $email = convert_data($this->input->post('email', true)); 
        $password = $this->input->post('password');

        $client = new Client("mongodb://localhost:27017");
        $admin = $this->collection =  $client->php_db->selectCollection('admin');

        $admin = $admin->findOne(['email'=> $email]);
        log_message('debug', 'found the user '.$email);
        if (!$admin || !isset($admin['password_hash']) || !password_verify($password, $admin['password_hash'])) {
            $this->session->set_flashdata('err', 'Invalid admin credentials.');
            redirect('login');
            return;
        }
        //Keep admin session to stay on logged in in order to load the dashboard
        $this->session->set_userdata([
        'admin_id'        => (string)$admin['_id'],
        'admin_email'     => (string)$admin['email'],
        'admin_logged_in' => true,
        ]);
        $this->session->sess_regenerate(true);
        log_message('debug','sucess auth');
        redirect('dashboard_login');
    }
    public function logout() {
        // Clear admin session
        $this->session->unset_userdata(['admin_id','admin_email','admin_logged_in']);
        $this->session->sess_regenerate(true);
        //load the home page again
        $this->load->view('templates/header');
        $this->load->view('pages/home');
        $this->load->view('templates/footer');

    } 

}