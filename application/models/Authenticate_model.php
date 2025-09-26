<?php

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

class Authenticate_model extends CI_Model {
    private $collection;
    public function __construct() {
        parent::__construct();
        $client = new Client("mongodb://localhost:27017");
        $this->collection =  $client->php_db->selectCollection('user');
    }


    public function authenticate($user_id) {
        //Give the verification unique code
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $token_hash = hash('sha256', $token); 

        //Update the document to include verification info 
        $this->collection->updateOne(
            ['_id' => $user_id],
            [
                '$set' => [
                'engagement.verified.token_hash'  => $token_hash,
                'audit.updated_at' => new UTCDateTime(),
                ]
            ]
        );
        //Create a link for the user to authenticate 
        $id_to_string = (string)$user_id;
        $verify_url = site_url('verify/email'). '?id='. $id_to_string . '&token=' . $token;
        return $verify_url;
    }
    public function return_token($user_id) {
        return $this->collection->findOne(
            ['_id' => $user_id]
        );
    }
}