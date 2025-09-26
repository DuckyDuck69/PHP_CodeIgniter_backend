<?php
use MongoDB\Client;

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_Session $session
 * @property SMailer $mailer
 * @property Queue $queue
 */

class AdminAuth extends CI_Controller {
    private $collection;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form_input');
        $this->load->library('session');
        $client = new Client("mongodb://localhost:27017");
        $this->collection =  $client->php_db->selectCollection('admin');
    }
    public function login() {

        $method = strtoupper($this->input->server('REQUEST_METHOD', TRUE));
        //If it is a GET method, load the admin_login page 
        if ($method=== 'GET') {
            $data['err'] = $this->session->flashdata('err');
            $this->load->view('pages/login', $data);
            return;
        }

        //Check credential
        $email = convert_data($this->input->post('email', true)); 
        $password = $this->input->post('password');

        $admin = $this->collection->findOne(['email'=> $email]);
        log_message('debug', 'found the user '.$email);
        if (!$admin || !isset($admin['password_hash']) || !password_verify($password, $admin['password_hash'])) {
            //$this->session->set_flashdata('err', 'Invalid admin credentials.');
            $data['err'] = 'Invalid admin credentials.';
            $this->load->view('templates/header');
            $this->load->view('pages/login', $data);
            $this->load->view('templates/footer');
            return;
        }
        //Keep admin session to stay on logged in in order to load the dashboard
        $this->session->set_userdata([
        'admin_id'        => (string)$admin['_id'],
        'admin_email'     => (string)$admin['email'],
        'admin_logged_in' => true,
        ]);
        $this->session->sess_update();
        log_message('debug','sucess auth');
        redirect('dashboard_login');
    }
    public function logout() {
        // Clear admin session
        $this->session->unset_userdata(['admin_id','admin_email','admin_logged_in']);
        $this->session->sess_update();
        //load the home page again
        $this->load->view('templates/header');
        $this->load->view('pages/home');
        $this->load->view('templates/footer');

    } 

}