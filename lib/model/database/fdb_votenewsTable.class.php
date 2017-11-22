<?php

/*
 * 该表存放新投票的投票结果
 */

class fdb_votenewsTable {
    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fdb_votenewsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('snsvote');
        }
        return self::$instance;
    }

    /*
     * 判断是否已经投过票
     */
    public static function ifHasVoted($voteid, $uid){
        $rs = mysql_query('select count(*) as num from fdb_votenews where voteid = '.$voteid .' and uid = '. $uid, self::$instance->db_connection);        
        $rs = mysql_fetch_assoc($rs);
        return $rs['num'] ? true : false;
    }
    
}