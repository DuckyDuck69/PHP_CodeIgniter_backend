<?php 
use MongoDB\Client;


defined('BASEPATH') OR exit('No direct script access allowed');
Class Mongo_db{
    private $client;
    private $db;
    public function __construct(){
        $this->client = new Client('mongodb://127.0.0.1:27017');
        $this->db =$this->client->selectDatabase('php_db');
    }

    //get a collection
    public function create_profile($name){
        return $this->db->selectCollection($name);
    }
}