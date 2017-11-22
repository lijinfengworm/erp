<?php

class hoop_cba_scheduleTable {

    protected $db_connection;
    protected $season;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_cba_scheduleTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("cba_tv_live");
            self::$instance->season = sfConfig::get('app_season');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

 
    
    public static function getCurrentRound(){
        $query = mysql_query('select lun,times from hoop_cba_schedule where home_score !=0 and away_score !=0 and season="'.self::$instance->season.'" order by times desc limit 1');
        $round = 1;
        $time = 0;
        $result = mysql_fetch_assoc($query);  
        if($result['lun']) $round = $result['lun'];
        if($result['times']) $time = $result['times'];
        $query = mysql_query('select id from hoop_cba_schedule where lun = '.$round.' and season="'.self::$instance->season.'"  and home_score=0 and away_score=0');
        $result2 = mysql_fetch_assoc($query);
        if($result2['id']) return $round;
        if(time() > $time) return $round++;
        return $lun;       
    }
    
    public static function getRoundByDay($day){
        $time_begin = strtotime(date($day.' 00:00:00')); 
        $query = mysql_query('select lun from hoop_cba_schedule where times < '.$time_begin.' and season="'.self::$instance->season.'"  limit 1');
        $round = 1;
        $time = 0;
        $result = mysql_fetch_assoc($query);  
        if(!$result['lun']) $round = 0;
        return $result['lun'];      
    }
    
    public static function getMatchsByRound($round){
        $query = mysql_query('select m.id, a.bbr_name as home_name, b.bbr_name as away_name, times, home_score, away_score, m.status, contents 
                    from hoop_cba_schedule as m 
                    left join hoop_cba_team as a on m.home_id = a.team_id
                    left join hoop_cba_team as b on m.away_id = b.team_id
                    where m.lun = '.$round.' and season = "'.self::$instance->season.'" order by m.times');
        $matches = array();
        while($row = mysql_fetch_assoc($query)){       
            $row['contents'] = mb_convert_encoding($row['contents'], 'utf-8', 'gbk');
            $row['home_name'] = mb_convert_encoding($row['home_name'], 'utf-8', 'gbk');
            $row['away_name'] = mb_convert_encoding($row['away_name'], 'utf-8', 'gbk');
            $matches[date('n月j日', $row['times'])][] = $row;
        }
        return $matches;
    }
    
    public static function getMatchById($match_id){        
        $query = mysql_query('select m.id, a.bbr_name as home_name, m.home_id, m.away_id, b.bbr_name as away_name, times, home_score, away_score, contents 
                    from hoop_cba_schedule as m 
                    left join hoop_cba_team as a on m.home_id = a.team_id
                    left join hoop_cba_team as b on m.away_id = b.team_id
                    where m.id = '.$match_id.' and season = "'.self::$instance->season.'" order by m.times');        
        $match = array();
        $row = mysql_fetch_assoc($query);
        if($row){            
            $row['home_name'] = mb_convert_encoding($row['home_name'], 'utf-8', 'gbk');
            $row['away_name'] = mb_convert_encoding($row['away_name'], 'utf-8', 'gbk');
            $match = $row;
        }
        return $match;
    }
    
    public static function getMatchesByDay($day){   
        $time_begin = strtotime(date($day.' 00:00:00'));
        $time_end = strtotime(date($day.' 23:59:59'));   
        $query = mysql_query('select m.id, a.bbr_name as home_name, m.home_id, m.away_id, b.bbr_name as away_name, lun, m.status, times, home_score, away_score, contents 
                    from hoop_cba_schedule as m 
                    left join hoop_cba_team as a on m.home_id = a.team_id
                    left join hoop_cba_team as b on m.away_id = b.team_id
                    where m.times <= '.$time_end.' and m.times >= '.$time_begin.' and season = "'.self::$instance->season.'" order by m.times');        
        $matches = array();        
        while($row = mysql_fetch_assoc($query)){            
            $row['home_name'] = mb_convert_encoding($row['home_name'], 'utf-8', 'gbk');
            $row['away_name'] = mb_convert_encoding($row['away_name'], 'utf-8', 'gbk');
            $row['contents'] = mb_convert_encoding($row['contents'], 'utf-8', 'gbk');
            $matches[] = $row;
        }
        return $matches;
    }
    
    
    public static function getPlayerInfoByMatchId($match_id){
        $query = mysql_query('select a.*,b.postion from hoop_cba_match as a left join hoop_cba_player as b on b.player_id=a.player_id  where a.match_id='.$match_id.'  order by a.sou desc');
        $info = array();
        while($row = mysql_fetch_assoc($query)){
            $row['player_name'] = mb_convert_encoding($row['player_name'], 'utf-8', 'gbk');
            $row['postion'] = mb_convert_encoding($row['postion'], 'utf-8', 'gbk');
            $info[$row['team_id']][] = $row;
        }
        return $info;
    }
    
    public static function getMatchNumberBetweenTime($begin, $end){
        $query = mysql_query('select count(*) as number from hoop_cba_schedule as m                     
                              where m.away_id>0 and m.times <= '.$end.' and m.times >= '.$begin.' and season = "'.self::$instance->season.'"');    
        $row = mysql_fetch_assoc($query);      
        return $row['number'];
    }
}

?>
