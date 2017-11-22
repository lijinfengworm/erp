<?php

class hcAMQPMessage {

    protected $data;

    public function __construct($data) {
        if (empty($data)) {
            throw new Exception('hcAMQPMessage expects $data as constructor parameter');
        }
        $this->data = $data;
    }

//  public function getSerialized()
//  {
//    return serialize($this->data);
//  }

    public function getJSON() {
        return json_encode($this->data);
    }

    public function getOptions() {
        return array('content_type' => 'application/octet-stream', 'delivery_mode' => 2);
    }

}