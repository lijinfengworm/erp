<?php

class hoopchina_db {

    protected $db_connection;
    protected static $instance;

    public static function getInstance($db_connection = null) {
        if (!isset(self::$instance)) {
            self::$instance = new hoopchina_db();
            if ($db_connection == null) {
                self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
                self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
                self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
                self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
            } else {
                self::$instance->db_connection = $db_connection;
            }
        }
        return self::$instance;
    }
    public function query($sql){
        return self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }

    public function getRepliesAndIfmail($id) {
        $query = self::query('select replies,ifmail from pw_threads where tid = ' . $id . ' limit 1;', self::$instance->db_connection);
        if ($query !== false) {
            return mysql_fetch_assoc($query);
        } else {
            return null;
        }
    }

    public function getImageNum($id) {
        $query = self::query('select count(pid) as num from fbd_photo where aid= ' . $id, self::$instance->db_connection);
        if ($query !== false) {
            $num = mysql_fetch_assoc($query);
            return $num['num'];
        } else {
            return 0;
        }
    }

}

?>
