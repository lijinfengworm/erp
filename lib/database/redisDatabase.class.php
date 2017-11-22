<?php
/**
 * Created by PhpStorm.
 * User: mayingying
 * Date: 2016/1/19
 * Time: 11:01

 */

class RedisDatabase extends sfDatabase {


    /**
     * Connects to the database.
     *
     * @throws <b>sfDatabaseException</b> If a connection could not be created
     */
    public function connect()
    {
        $host = $this->getParameter('host');
        $port = $this->getParameter('port');
        $this->connection = new Redis();
        $this->connection->connect($host, $port);

        if ($this->connection === false){
            throw new sfDatabaseException('Failed to connect redis.');
        }
    }

    /**
     * Executes the shutdown procedure.
     *
     * @return boolean
     *
     */
    public function shutdown()
    {
        if($this->connection)
        {
            return $this->connection->close();
        }
    }
}