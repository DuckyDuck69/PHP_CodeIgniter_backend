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
    public function create_open_tracking(string $id){
        $result = $this->collection->updateOne(['_id' => new ObjectId($id)], 
                                    ['$set' => ['engagement.email_open.opened' => false, 
                                                        'engagement.email_open.first_opened_at' => null,
                                                        'audit.updated_at' => new UTCDateTime()]]);
        return $result->getModifiedCount() > 0;
    }
    public function update_open_tracking(string $id){
        $result = $this->collection->updateOne(['_id' => new ObjectId($id), 
                                                'engagement.email_open.opened' => ['$ne' => true]  ], //only update if opened is false
                                    ['$set' => ['engagement.email_open.opened' => true, 
                                                        'engagement.email_open.first_opened_at' => new UTCDateTime(),
                                                        'audit.updated_at' => new UTCDateTime()]]);
        return $result->getModifiedCount() > 0;
    }

    public function record_pdf_click(string $id){
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



}