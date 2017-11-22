<?php

/*
 * text直播
 */

class hoop_matchTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_matchTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hooplive');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    public static function getMatchChinaTimeAndTeamsName($match_id) {
        $rs = self::query('SELECT home_team_name, home_team, away_team_name, away_team, match_china_time FROM `hoop_match` where match_id =' . $match_id . ' limit 1');
        if (!$rs) {
            return null;
        }
        return mysql_fetch_assoc($rs);
    }

    /*
     * return array(0=> '比赛是否结束', 1=>'当前比分', 2=>'这节比赛剩余时间', 3=>'比赛进行到第几节')
     */
    public static function getMatchStatus($match_id) {
        if (is_numeric($match_id)) {
            $sql = 'select tValue as v from txt_data where tKey = "matchdata_final_' . $match_id . '" union all select tValue as v from txt_data where tKey = "matchdata_score_' . $match_id . '" union all select tValue as v from txt_data where tKey = "matchdata_time_' . $match_id . '" union all select tValue as v from txt_data where tKey = "matchdata_bisai' . $match_id . '"';
            $rs = self::query($sql);
            if ($rs) {
                $ary = array();
                while ($a = mysql_fetch_assoc($rs)) {
                    $ary[] = $a['v'];
                }
                return $ary;
            }
        }
        return null;
    }

}