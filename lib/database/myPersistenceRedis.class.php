<?php
class myPersistenceRedis extends sfDatabase
{
    public function connect(){
        $host     = $this->getParameter('host');
        $port     = $this->getParameter('port');

        $this->connection = new tradeRedisPersistence();
        $this->connection->connect($host, $port);


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