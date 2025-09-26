<?php 
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

/**
 * @property CI_Input $input
 * @property Mailer $mailer
 * @property Queue $queue 
 * @property Track_model $track
 */

class Verify extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library("mailer");
        $this->load->model("Track_model", 'track');
        $this->load->library('queue');
    }

    /**
     * Use GET to read the id and token, then compare with what are in the 
     * DB, if they match => update verify status, else tell the user they failed to verify
     */
    public function email(){
        $id = $this->input->get("id", true);
        $token = $this->input->get("token", true);
        log_message('debug', 'Initializing Verify Controller');
        if(!$id || !$token){
            show_404();
        }

        //Connect to mongo and collection
        $client = new Client('mongodb://localhost:27017');
        $db = $client->selectDatabase('php_db');
        $collection = $db->selectCollection('user');

        //Build timestamp
        $now = new UTCDateTime();
        log_message('debug', 'Begin to verify');
        $result = $collection->updateOne(
           [ '_id' => new ObjectId($id), //convert string id to mongodb id
                    'engagement.verified.token_hash' => hash('sha256', $token)  //hash the token string to compare with token_hash
                    ],

            [
                        '$set' => ['engagement.verified.status' => true, 
                                    'engagement.verified.verified_at' => $now,
                                    'audit.updated_at' => new UTCDateTime()],
                        '$unset' => ['engagement.verified.token_hash' => '' ]                    
                    ]
        );

        if ($result->getModifiedCount() === 1) {
            //find the mached correct BSON profile
            $profile = $collection->findOne(['_id' => new ObjectId($id) ]); 
            $user_name = $profile['identity']['full_name'];
            $user_email = $profile['identity']['email'];
            $attachment = [];
            $subject = 'ST Group: Gui Tep ';
            $pdf_url = site_url('track').'/pdf?id=' . $id;
            $body = 'Xin chào '.$user_name
                        .'<br><br>Đây là tệp đính kèm của bạn: ' 
                        .'<a href="'.$pdf_url.'">Tải pdf của bạn</a>'
                        .'<br><br> Trân trọng 
                        '.'<br> ST Group';
            $payload = [
                'to_email'    => $user_email,
                'to_name'     => $user_name,
                'subject'     => $subject,
                'body_html'   => $body,
                'alt_body' => '',
                'attachments' => []
            ];
            $this->queue->putInTube('smailer', $payload, 100, 0, 10);
            
            //initialize tracking
            log_message('debug', 'Sucess to verify ');

            redirect(site_url('verify_success')); 
        }else{
            log_message('debug', 'Failed to verify ');
            redirect(site_url('verify_failed'));
        }
    }
}