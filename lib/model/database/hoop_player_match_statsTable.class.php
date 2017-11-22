<?php
/*
 * 
 */

class hoop_player_match_statsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_player_match_statsTable();
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

    public static function getTeamsByMatch_id($match_id) {
        $rs = mysql_query('select home_team, home_score, home_team_name, away_team, away_score, away_team_name from hoop_match where match_id= '. $match_id);
        if(!$rs){
            return false;
        }
        return mysql_fetch_assoc($rs);
    }

    public static function getTeamPlayersDataByMatch_id($match_id, $home_team_id, $away_team_id ){
        $sql = 'SELECT player_name,team_id,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,asts,stl,blk,`to`,pf,dnp,starter,pfa, player_name_cn FROM hoop_player_match_stats left join hoop_player on hoop_player.player_id = hoop_player_match_stats.player_id WHERE match_id='.$match_id.' ORDER BY team_id, starter desc';
        $rs = self::query($sql);
        if(!$rs){
            return null;
        }
        $data = array();
        while($row = mysql_fetch_assoc($rs)){
            if($row['team_id']==$home_team_id){
                $data['home_team'][] = $row;
            }else{
                $data['away_team'][] = $row;
            }
        }
        return $data;
    }
    
}