<?php
class Pages extends CI_Controller{

    public function index() {
        // default landing 
        $this->view('home');
    }
    public function view($page = 'home'){
        if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
            //We don't have a page that is requested
            show_404();
        }

        $data['title'] = ucfirst($page);  //capitalize first letter

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }
    public function home(){
        $this->load->view('templates/header');
        $this->load->view('pages/home');
        $this->load->view('templates/footer');
    }
    public function thank_you() {
        $this->load->view('pages/thank_you'); 
    }
    public function verify_success() {
        $this->load->view('pages/verify_success'); 
    }
    
    public function form() {
        $this->load->view('pages/form'); 
    }

    public function login() {
        $this->load->view('templates/header');
        $this->load->view('pages/login');
        $this->load->view('templates/footer');
    }

}
