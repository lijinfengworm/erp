<?php
class myTTDatabase extends sfDatabase
{
  public function connect(){
    $host     = $this->getParameter('host');
    $port     = $this->getParameter('port');

    $this->connection = new memcache();
    $this->connection->connect($host, $port);
    
    if ($this->connection === false){
      throw new sfDatabaseException('Failed to create a ttServer connection.');
    }
  }

  public function shutdown(){
    if ($this->connection != null){
      $this->connection->close();
    }
  }
}

