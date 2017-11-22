<?php
/*
 * 存放推荐帖子、视频的信息的帖子
 * type: 被推荐的内容的种类 1帖子
 * cid：被推荐的内容，如帖子的tid, 投票的voteid等
 */
class fbd_rm_allTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fbd_rm_allTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hc_friendevent_rec');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    /*
     * 返回推荐该内容的人数
     * $type: 被推荐的内容的种类 1帖子
     * $cid: 被推荐内容的ID
     */

    public static function getRecommendedNum($cid, $type=1) {
        $sql = "SELECT count(*) as num FROM fbd_rm_all WHERE type=$type AND cid=$cid";
        $query = mysql_query($sql, self::$instance->db_connection);
        if (!$query) {
            return 0;
        } else {
            $num = mysql_fetch_assoc($query);
            return (int) $num['num'];
        }
    }

}
