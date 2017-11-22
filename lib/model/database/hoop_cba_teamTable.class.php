<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * 球队的基本信息
 */
class hoop_cba_teamTable {
    
    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_cba_teamTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('cba_tv_live');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }
 
   /**
    * 
    * @param 
    * @return 球队的基本信息
    */
  public static function getCbaTeam() {
        $rs = self::query('SELECT team_id,team_name,all_name,bbr_name,team_name_pinyin,status FROM `hoop_cba_team`');
        if (!$rs) {
            return null;
        }
       
       $ary = array();
       while ($a = mysql_fetch_assoc($rs)) {
                    $a['team_name'] = mb_convert_encoding($a['team_name'], "UTF-8", "GBK"); 
                    $ary[] = $a;
       }
       return $ary;
    }
    
    
    
    
}