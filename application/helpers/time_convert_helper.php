<?php
use MongoDB\BSON\UTCDateTime;
function convert_time($time){
    $datetime = $time->toDateTime();
    return $datetime->format('Y-m-d H:i:s');
}