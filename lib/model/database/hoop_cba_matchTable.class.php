<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * 球员每场比赛的信息统计
 */
class hoop_cba_matchTable {
    
    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_cba_matchTable();
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
    * @return 获取本赛季球员的每场比赛的信息
    */
  public static function getCbaPerSeason($season = NULL) {
  
        $rs = self::query('SELECT * FROM `hoop_cba_match` where season ="'.$season.'"');
        if (!$rs) {
            return null;
        }
       
       $ary = array();
       while ($a = mysql_fetch_assoc($rs)) {
                    $a['player_name'] = mb_convert_encoding($a['player_name'], "UTF-8", "GBK"); 
                    $ary[] = $a;
       }
       return $ary;
    }
    
  
    
    
}