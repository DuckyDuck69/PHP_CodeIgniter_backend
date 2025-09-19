// application/controllers/Tools/AdminSeed.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

class AdminSeed extends CI_Controller {
    public function __construct(){ 
        parent::__construct(); 
    }
    public function create() {
        //Only create using CLI
        if (!$this->input->is_cli_request()){
            show_404();
        } 

        $email = getenv('ADMIN_EMAIL') ?: 'admin@southtelecom.vn';
        $pass  = getenv('ADMIN_PASS')  ?: 'nopass';

        
        $client = new Client('mongodb://localhost:27017');
        $admins = $client->php_db->selectCollection('admin');

        if ($admins->findOne(['email'=>$email])){ 
            echo "Admin exists\n"; return; 
        }

        $admins->insertOne([
            'email'=>$email,
            'password_hash'=>password_hash($pass, PASSWORD_BCRYPT),
            'created_at'=>new UTCDateTime()
            ]);
        echo "Admin created: $email\n";
    }
}
