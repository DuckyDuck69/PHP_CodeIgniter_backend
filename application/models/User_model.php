<?php
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
class User_model extends CI_Model {
    private $collection;
    public function __construct() {
        parent::__construct();
        $client = new Client("mongodb://localhost:27017");
        $this->collection =  $client->php_db->selectCollection('user');
    }
    public function create_user(string $user_email, 
                                string $user_name,
                                string $user_age,
                                string $user_gender,
                                string $user_job,
                                string $user_longitude,
                                string $user_latitude) {
        //Prepare a document to insert to the collection
        $document = [
            'identity' => [
                'email'     => $user_email,
                'full_name' => $user_name,
                'role' => 'user'
            ],
            'profile' => [
                'age'        => (int)$user_age,
                'gender'     => $user_gender,
                'occupation' => $user_job,
            ],
            'location' => [
                'coords' => [
                    'type'        => 'Point',
                    'coordinates' => [(float) $user_longitude, (float) $user_latitude],
                ],
            ],
            'engagement' => [
                'verified' => [
                    'status'     => false,
                    'token_hash' => '',     
                    'verified_at'         => null,   
                ],
                'email_open' => [
                    'opened' => false,
                    'first_opened_at' => null  // set once, never overwrite
                ],
                'pdf_click' => [
                    'clicked' => false,
                    'first_clicked_at' => null
                ],
            ],
            'audit' => [
                'created_at' => new UTCDateTime(),  
                'updated_at' => new UTCDateTime()
            ],
        ];
        //store the person in the collection
        $result = $this->collection->insertOne($document);

        //give the person a unique id
        $user_id = $result->getInsertedId();
        $document = $this->collection->findOne(['_id' => $user_id]);

        return $user_id;
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