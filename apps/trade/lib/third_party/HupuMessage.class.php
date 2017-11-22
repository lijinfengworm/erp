<?php
class HupuMessage
{
  private $url;
  private $senderId;
  private $senderUsername;
  private $receiverId;
  private $receiverUsername;
  private $subject;
  private $message;
  
  public function __construct($url, $senderId, $senderUsername, $receiverId, $receiverUsername, $subject, $message)
  {
    $this->url = $url;
    $this->senderId = $senderId;
    $this->senderUsername = $senderUsername;
    $this->receiverId = $receiverId;
    $this->receiverUsername = $receiverUsername;
    $this->subject = $subject;
    $this->message = $message;
  }
  
  public function send()
  {
    return file_get_contents($this->getFinalUrl());
  }
  
  private function getFinalUrl()
  {
    $url = $this->url.'?fe_uname='.mb_convert_encoding($this->senderUsername, 'GBK', 'UTF-8')
                     .'&fe_uid='.$this->senderId
                     .'&touid='.$this->receiverId
                     .'&touname='.mb_convert_encoding($this->receiverUsername, 'GBK', 'UTF-8')
                     .'&title='.urlencode(mb_convert_encoding($this->subject, 'GBK', 'UTF-8')) 
                     .'&content='.urlencode(mb_convert_encoding($this->message, 'GBK', 'UTF-8'));
                     
    return $url;
  }
  
  public function toString()
  {
    return $this->getFinalUrl();
  }
}