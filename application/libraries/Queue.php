<?php
//----------------------------------------------------
//Helper to wrap Pheanstalk so the rest of the website 
//can perform push(), reserve(), delete(), etc.
//----------------------------------------------------

use Pheanstalk\Pheanstalk;

class Queue {

    //Note: use protected to allow child classes to inherit 
    //////and extend behavior
    protected $client;

    public function __construct($params = []){
        //Find the Beanstalk server
        $host = isset($params['host']) ? $params['host'] : '127.0.0.1'; //if none provided, choose 127.0.0.1
        $port = isset($params['port']) ? $params['port'] : 11300;

        //Create Pheastalk client with TCP connection to server 
        $this->client = new Pheanstalk($host, $port);
    }

    /**
     * Put a job into the tube.
     * + $payload: an JSON-encode array - **message body**
     * + $priority: lower number = higher priority
     * + $delay: seconds to wait before job ready
     * + $ttr aka time-to-run: seconds the worker finish this job
     */
    public function putInTube( $tube,  $payload,  $priority=100,  $delay=0,  $ttr=20) {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        return $this->client
                ->putInTube($tube, $json, $priority, $delay, $ttr);
    }

    /**
     * Reserve , or Fetch, one job from the tube with a timeout.
     * + If a job is available, Beanstalkd moves it to "reserved" and deliver it
     * + If not available within $timeout seconds, returns null.
     */
    public function reserveFromTube( $tube,  $timeout = 10){
        return $this->client->watch($tube)  //observe the tube we choose
                            ->ignore('default')
                            ->reserve($timeout);
    }

    /**
     * Mark job done, remove from the queue
     */
    public function delete($job){
        return $this->client->delete($job);
    }

    /**
     * Put job back into queue to try later
     */
    public function release($job,  $priority = 1024,  $delay = 10){
        $this->client
        ->release($job, $priority, $delay);  //this is PHEASTALK release function
    }

    /**
     * Bury: move to a holding state for inspection manually
     *after too many failures
     */
    public function bury($job){
        $this->client->bury($job);
    }

}

