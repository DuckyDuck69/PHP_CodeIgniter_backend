<?php
/**
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property User_model $user_model
 * @property Mailer $mailer
 * @property Queue $queue 
 */
class Admin extends CI_Controller {
    //TODO: refactor this class
    public function __construct() {
        parent::__construct();
        $this->load->library("session");
        $this->load->model("user_model");
        $this->load->library('mailer');
        $this->load->library('queue', ['host' => '127.0.0.1', 'port' => 11300]); 

    }
    public function dashboard() {
        //Check if the current session belongs to an admin
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('login');
            return;
        }

        //Read and extract filter option using GET 
        $filter_key = (string) $this->input->get('filter', true);
        $criteria = $this->buildCriteria($filter_key);

        //Keep the filter dropdown consistent
        $active_filter = $filter_key === '' ? 'none' : $filter_key;

        //Retrive the filtered users based on the criteria 
        $users = $this->user_model->retrieve_user($criteria);
        $this->load->view('pages/dashboard', ['user' => $users, 'active_filter' => $active_filter]); 
    }

    public function update_dashboard() {
        //Verify admin session 
        if (!$this->session->userdata('admin_logged_in')) { 
            redirect('login'); 
            return; 
        }

        //Accept POST only
        if (strtoupper($_SERVER['REQUEST_METHOD'] ) !== 'POST') {
            redirect('dashboard_login');
            return;
        }

        //Get the option from the dashboard dropdown
        $selected = (string) $this->input->post('selectedOption', true);
        $allowed  = ['none', 'email_verified', 'email_not_verified', 'pdf_clicked', 'pdf_not_clicked'];
        if (!in_array($selected, $allowed, true)) { //Handle edge cases
            $selected = 'none';
        }

        //Use PRG: redirect to a GET URL
        if ($selected === 'none' || $selected === '') {
            redirect('admin/dashboard'); // no filter param
        } else {
            redirect('admin/dashboard?filter=' . rawurlencode($selected));
        }
    }

    public function send_reminder(){
        if (!$this->session->userdata('admin_logged_in')) { 
            redirect('login'); 
            return; 
        }

        //Ensure POST for safe data
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            redirect('dashboard_login');
            return; 
        }

        ///Extract the ids from the filters again to build the email
        $filter_key = (string)$this->input->post('filter', true);
        $criteria   = $this->buildCriteria($filter_key); 

        $users = $this->user_model->retrieve_user($criteria);
        //Keep track of how many ppl we send
        $sent = 0;
        //Send out a pdf file or an email reminder based on each condition
        foreach ($users as $user) {
            $id         = isset($user['_id']) ? (string)$user['_id'] : '';
            $user_name  = isset($user['identity']['full_name']) ? (string)$user['identity']['full_name']: '';
            $user_email = isset($user['identity']['email']) ? (string)$user['identity']['email'] : '';
            if($filter_key === 'email_not_verified'){
                $user_token = $user['engagement']['verified']['token_hash'];
                $verify_url = site_url('verify/email'). '?id='. $id . '&token=' . $user_token;
                $subject = 'ST Group: Xac nhan tai khoan';
                $body = 'Xin chào '.$user_name.'<br><br>Xin vui lòng bấm vào đường link này để xác nhận: '
                    . '<a href="'.$verify_url.'">Kích hoạt tài khoản</a>';                       
            }else if ($filter_key === 'pdf_not_clicked'){
                $subject = 'ST Group: Gui Tep ';
                $img_url = 'http://localhost:8081/training_homework/index.php/track/user_opened?id=' . $id;
                $pdf_url = 'http://localhost:8081/training_homework/index.php/track/pdf?id=' . $id;
                $body = 'Xin chào '.$user_name
                            .'<br><br>Đây là tệp đính kèm của bạn: ' 
                            .'<a href="'.$pdf_url.'">Tải pdf của bạn</a>'
                            .'<br><br> Trân trọng 
                            '.'<br> ST Group
                            <img src="'.$img_url.'" width="1" height="1" style="display:none;" alt="">
                            <img src="'.$img_url.'&method=block" width="1" height="1" style="display:block;opacity:0;" alt="">
                            <div style="background-image: url(\''.$img_url.'&method=css\'); width:1px; height:1px; opacity:0;"></div>'; 
            }else{
                continue;
            }
            $payload = [
                'to_email'    => $user_email,
                'to_name'     => $user_name,
                'subject'     => $subject,
                'body_html'   => $body,
                'alt_body' => '',
                'attachments' => []
            ];
            $jobId = $this->queue->putInTube('smailer', $payload, 100, 0, 10); 
            $sent++;  
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'ok' => true,
                'message' => 'Queued reminders successfully.',
                'sent' => $sent
            ]));
    }

    //Helper to return filter options 
    private function buildCriteria($filter_key) {
        switch ($filter_key) {
            case 'email_verified':     return ['engagement.verified.status' => true];
            case 'email_not_verified': return ['engagement.verified.status' => false];
            case 'pdf_clicked':        return ['engagement.pdf_click.clicked' => true];
            case 'pdf_not_clicked':    return ['engagement.pdf_click.clicked' => false];
            default:                   return [];
        }
}
}