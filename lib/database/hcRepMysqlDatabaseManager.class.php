<?php

class hcRepMysqlDatabaseManager extends sfDatabase {
    public function connect() {
        $this->connection = new hcRepMysqlDatabase($this->getParameter('write_group'),$this->getParameter('read_group'),$this->getParameter('role'));
    }

    public function shutdown() {

    }

}
