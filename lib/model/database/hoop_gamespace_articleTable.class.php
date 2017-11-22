<?php
/*
 * 战报
 */

class hoop_gamespace_articleTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_gamespace_articleTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hc_www');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    public static function getArticleByMatch_id($match_id) {
        $rs = mysql_query('select article_title, article_digest, submit_time, match_id from hoop_gamespace_article where match_id='. $match_id . ' order by article_id desc limit 1');
        if(!$rs){
            return false;
        }
        return mysql_fetch_assoc($rs);
    }



}