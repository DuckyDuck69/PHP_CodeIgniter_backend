<?php
class Pages extends CI_Controller{
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
}
