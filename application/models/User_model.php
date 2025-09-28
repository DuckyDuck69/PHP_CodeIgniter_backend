<?php
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
class User_model extends CI_Model {
    private $collection;
    public function __construct() {
        parent::__construct();
        $this->load->library('queue', ['tube' => 'emails']); 
        $this->load->helper('time_convert');
        $client = new Client("mongodb://localhost:27017");
        $this->collection =  $client->php_db->selectCollection('user');
    }
    public function create_user($user_email, 
                                $user_name,
                                $user_age,
                                $user_gender,
                                $user_job,
                                $user_longitude,
                                $user_latitude) {
        //Prepare a document to insert to the collection
        $document = [
            'identity' => [
                'email'     => $user_email,
                'full_name' => $user_name,
                'role' => 'user'
            ],
            'profile' => [
                'age'        => $user_age,
                'gender'     => $user_gender,
                'occupation' => $user_job,
            ],
            'location' => [
                'coords' => [
                    'type'        => 'Point',
                    'coordinates' => [$user_latitude, $user_longitude],
                ],
            ],
            'engagement' => [
                'verified' => [
                    'status'     => false,
                    'token_hash' => '',     
                    'verified_at'         => null,   
                ],
                'email_open' => [
                    'verify' => false,
                    'verify_opened_time' => null,
                    'pdf' => false,
                    'pdf_opened_time' => null  
                ],
                'pdf_click' => [
                    'clicked' => false,
                    'first_clicked_at' => null
                ],
            ],
            'audit' => [
                'created_at' => convert_time(new UTCDateTime()),  
                'updated_at' => convert_time(new UTCDateTime())
            ],
        ];
        //store the person in the collection
        $result = $this->collection->insertOne($document);

        //give the person a unique id
        $user_id = $result->getInsertedId();
        $document = $this->collection->findOne(['_id' => $user_id]);

        return $user_id;
    }

    public function get_user($id) {
        try {
            $oid = new ObjectId($id);
        } catch (\Exception $e) {
            log_message('error', 'Invalid ObjectId: '.$id);
            return null;
        }
        return $this->collection->findOne(['_id' => $oid]) ?: null;
    }

    public function retrieve_user(array $filter = []){
        //Tell the driver to return PHP type document and array
        $opts = [
        'typeMap' => [
            'root'     => 'array',  // top-level document
            'document' => 'array',  // any sub-document
            'array'    => 'array',  // BSON arrays
        ]
        ];
        //retrive all documents
        $cursor = $this->collection->find($filter, $opts);
        return iterator_to_array($cursor, false);
    }

}