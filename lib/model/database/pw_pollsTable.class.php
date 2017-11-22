<?php
/*
 * 老的投票信息存放位置
 */
class pw_pollsTable {
    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_pollsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }

        return self::$instance;
    }

    /*
     *  2：投票不存在 true:成功
     *  允许多次投票
     */
    public static function vote($voteid, $username, $selecter){
        $rs = self::$instance->db_connection->query('select voteopts from pw_polls where pollid ='.$voteid, self::$instance->db_connection);
        $rs = mysql_fetch_assoc($rs);
        if(is_array($rs)){
            $vote = unserialize($rs['voteopts']);
            $vote['options'][$selecter-1][1] = $vote['options'][$selecter-1][1] + 1;
            array_push($vote['options'][$selecter-1][2], $username);
            $vote = serialize($vote);
            self::$instance->db_connection->query("update pw_polls set voteopts = '$vote' where pollid = ".$voteid, self::$instance->db_connection);
            return true;
        }else{
            return 2;
        }
    }
}