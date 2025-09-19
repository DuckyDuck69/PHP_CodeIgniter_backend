<?php
require __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->selectDatabase("php_db");

$result = $db->selectCollection("test")->insertOne(["hello" => "world"]);
echo "Inserted with ID: " . $result->getInsertedId() . PHP_EOL;