<?php 
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Verify extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library("mailer");
        $this->load->model("Track_model", 'track');
        
    }
    public function email(){
        $id = $this->input->get("id", true);
        $token = $this->input->get("token", true);

        if(!$id || !$token){
            show_404();
        }

        //Connect to mongo and collection
        $client = new Client('mongodb://localhost:27017');
        $db = $client->selectDatabase('php_db');
        $collection = $db->selectCollection('user');

        //Build timestamp
        $now = new UTCDateTime();

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
            $this->mailer->send($user_email, $user_name,  $subject, $body, '', $attachment);
            
            //initialize tracking
            $this->track->create_open_tracking($id);
            redirect('verify_success'); 
        }else{
            //log_message('debug', );
            redirect('verify_failed');
        }
    }
}