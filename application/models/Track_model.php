<?php

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
class Track_model extends CI_Model{
    private $collection;
    public function __construct(){
        parent::__construct();
        $client = new Client("mongodb://localhost:27017");
        $this->collection =  $client->php_db->selectCollection('user');
    }
    public function update_open_tracking( $id){
        $result = $this->collection->updateOne(['_id' => new ObjectId($id), 
                                                'engagement.email_open.opened' => ['$ne' => true]  ], //only update if opened is false
                                    ['$set' => ['engagement.email_open.opened' => true, 
                                                        'engagement.email_open.first_opened_at' => new UTCDateTime(),
                                                        'audit.updated_at' => new UTCDateTime()]]);
        return $result->getModifiedCount() > 0;
    }

    public function record_pdf_click( $id){
        $result = $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            [
                '$set' => [
                    'engagement.pdf_click.clicked'    => true,
                    'engagement.pdf_click.first_clicked_at' => new UTCDateTime(),
                    'audit.updated_at' => new UTCDateTime()
                ]
            ]
        );
        return $result->getModifiedCount() > 0;
    }
    public function record_verify_email_opened($id){
        $result = $this->collection->updateOne(
                    ['_id' => new ObjectId($id), 'engagement.email_open.verify'=> ['$ne' => true]],
                    [
                        '$set' => [
                            'engagement.email_open.verify'    => true,
                            'engagement.email_open.verify_opened_time' => new UTCDateTime(),
                            'audit.updated_at' => new UTCDateTime()
                        ]
                    ]
                );
        return $result->getModifiedCount() > 0;
    }
    public function record_pdf_email_opened($id){
        $result = $this->collection->updateOne(
                    ['_id' => new ObjectId($id), 'engagement.email_open.pdf'=> ['$ne' => true]],
                    [
                        '$set' => [
                            'engagement.email_open.pdf'    => true,
                            'engagement.email_open.pdf_opened_time' => new UTCDateTime(),
                            'audit.updated_at' => new UTCDateTime()
                        ]
                    ]
                );
        return $result->getModifiedCount() > 0;
    }
}