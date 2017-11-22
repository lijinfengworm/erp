<?php
class myredis extends sfDatabase
{
  public function connect(){
    $host     = $this->getParameter('host');
    $port     = $this->getParameter('port');

    $this->connection = new redis();
    $this->connection->connect($host, $port);
    

    if($this->hasParameter("username") &&  $this->hasParameter("password")) {
      if($this->connection->auth($this->getParameter("username").":".$this->getParameter("password")) ==false) {
        throw new sfDatabaseException("Failed to auth a redis connection");
      }
    }


    if ($this->connection === false){
      throw new sfDatabaseException('Failed to create a redis connection.');
    }
    if($this->hasParameter('house')){
        $this->connection->select($this->getParameter('house'));
    }
  }

  public function shutdown(){
    if ($this->connection != null){
      $this->connection->close();
    }
  }
}

