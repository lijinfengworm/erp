<?php

/*
 * 该表用于统计某一个用户在某个板块一周内的发帖数
 */

class fbd_cou_thread_newTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fbd_cou_thread_newTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    public static function query($sql) {
        return self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }

    /*
     * 一周内用户是否发过帖
     */

    public static function ifHasDoneInThisWeek($uid, $fid, $today_time) {
        $rs = self::query('select count(id) as num from fbd_cou_thread_new where fid=' . $fid . ' and uid=' . $uid . ' and time=' . $today_time);
        if(!$rs) return false;
        $arr = mysql_fetch_assoc($rs);
        if ($arr['num']) {
            return true;
        }
        return false;
    }

    /*
     * 更新用户在该板块的本周回帖记录
     */

    public static function updateUserInfo($uid, $fid) {
        $today_time = mktime(0, 0, 0, date('n', time()), date('j', time()), date('Y', time()));
        if (self::ifHasDoneInThisWeek($uid, $fid, $today_time)) {
            self::query('update fbd_cou_thread_new set num=num+1 where fid=' . $fid . ' and uid=' . $uid . ' and time=' . $today_time);
        } else {
            self::query('INSERT INTO fbd_cou_thread_new (id ,fid ,uid ,time ,num) VALUES (NULL , ' . $fid . ', ' . $uid . ', ' . $today_time . ', 1)');
            self::query('delete from fbd_cou_thread_new where uid=' . $uid . ' and time <=' . ($today_time - 7 * 24 * 3600)); /* 7天以前的可以删除了 */
        }
    }

}