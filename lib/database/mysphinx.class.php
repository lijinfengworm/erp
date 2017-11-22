<?php
class mysphinx extends sfDatabase
{
  public function connect(){
    $host     = $this->getParameter('host');
    $port     = $this->getParameter('port');
    $this->connection = mysql_connect($host.':'.$port);
    if ($this->connection === false){
      throw new sfDatabaseException('Failed to create a sphinx connection.');
    }
  }

  public function shutdown(){
    if ($this->connection != null){
      mysql_close($this->connection);
    }
  }
}

