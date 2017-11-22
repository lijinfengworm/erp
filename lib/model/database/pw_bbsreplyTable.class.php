<?php

/*
 * 该表用来统计每天不同操作的数量
 */

class pw_bbsreplyTable {

    protected $db_connection;
    protected static $instance;
    protected static $today;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_bbsreplyTable();
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
     * 今天该操作是否已有记录
     */

    public static function hasLoged($uid) {
        $rs = self::query('select num,date,todaynum from pw_bbsreply where uid='.$uid);
        $rs = mysql_fetch_assoc($rs);
        if (empty($rs)) {
            return null;
        }
        return $rs;
    }
    /*
     * 数据更新
     */
    public static function updatePw_bbsreply($uid) {
        $rs = self::hasLoged($uid);
        $time = time();
        if ($rs) {
            if (date("Y-m-d", $rs['date']) == date("Y-m-d")) {
                if ($rs['todaynum'] < 30) {
                    self::query('UPDATE pw_bbsreply SET num = num+1,todaynum=todaynum+1,date='.$time.' WHERE uid = '.$uid);
                }
            } else {
                self::query('UPDATE pw_bbsreply SET num = num+1,todaynum=1,date='.$time.' WHERE uid ='.$uid);
            }
        } else {
            $rs = self::query('SELECT sum(tid) AS num FROM pw_threads WHERE `authorid` = '. $uid);
            $rs = mysql_fetch_assoc($rs);
            $rs2 = self::query('select postnum from pw_memberdata where uid='.$uid);
            $rs2 = mysql_fetch_assoc($rs2);
            $num = $rs2['postnum'] - $rs['num'];
            $num = $num <= 0 ? 1 : $num;
            self::query('INSERT INTO pw_bbsreply (id ,uid ,date ,num,todaynum)VALUES (NULL , '.$uid.', '.$time.', '.$num.',1)');
        }
    }

}

