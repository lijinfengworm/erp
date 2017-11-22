<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * 每场比赛球队的相关信息
 */
class hoop_cba_matchdatatotalTable {
    
    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_cba_matchdatatotalTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('cba_tv_live');
//            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
//            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }
 
   /**
    * 
    * @param 
    * @return 获取本赛季球度每场比赛的信息
    */
  public static function getCbaTeamSeason($season = NULL) {
//       $sql = 'SELECT  a.home_id,a.away_id,a.season,b.*
//                FROM hoop_cba_schedule AS a
//                LEFT JOIN `hoop_cba_matchdatatotal` AS b ON a.id = b.match_id
//                WHERE a.season = "'.$season.'"';
//       echo $sql;
        $sql = 'SELECT a.home_id,a.away_id,a.season,b.* FROM `hoop_cba_schedule` as a , hoop_cba_matchdatatotal as b  where a.season="'.$season.'" and a.id = b.match_id order by a.id desc';
        $rs = self::query($sql);
        if (!$rs) {
            return null;
        }
       
       $ary = array();
       while ($a = mysql_fetch_assoc($rs)) {
                    $ary[] = $a;
       }
       return $ary;
    }
    
  
    
    
}