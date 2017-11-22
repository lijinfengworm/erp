<?php

/*
 * 该表主要存放了用在 “我的空间” -> “帖子” -> “回帖”中调用到的数据
 * 分表规则 fbd_all_reply_(用户ID%100)
 */

class fbd_all_replyTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fbd_all_replyTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('fbd_all_reply');
        }
        return self::$instance;
    }

    private static function  query($sql){
        return mysql_query($sql, self::$instance->db_connection);
    }

    /*
     * 回帖后插入一条记录
     */
    public static function insertLog($uid, $tid, $pid, $fid) {
        $time = time();
        self::query("insert into ". self::getTableName($uid) ."(uid, tid, pid, fid, `time`) values ( $uid, $tid, $pid, $fid, $time)");
    }

    private static function getTableName($uid) {
        $mytable = $uid % 100;
        if ($mytable < 10) {
            $mytable = "0" . $mytable;
        }
        return  "fbd_all_reply_" . $mytable;
    }

}