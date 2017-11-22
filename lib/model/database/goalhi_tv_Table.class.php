<?php 

 class goalhi_tv_Table{
   protected $db_connection;
   protected static $instance;

     public static function getInstance(){
	  if(!isset(self::$instance)){
	    	self::$instance = new goalhi_tv_Table();
                self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('goalhi_tv_live');
                mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
		mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
		mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
            }			
	  
	  return self::$instance;
	 }

/*
  获得足球直播的前10条信息(按比赛时间排序)
*/
   public static function getAll_GHlive(){
	   $result = mysql_query("select id,date,teama,teamb,zhibo from wc_match ORDER BY date DESC LIMIT 0,10");
       
	   $arr = array();
	   while($row = mysql_fetch_assoc($result)){
		   $row["league"]= 3;
	   $row["teamb"]=mb_convert_encoding($row["teamb"],'utf8','gb2312');
           $row["teama"]=mb_convert_encoding($row["teama"],'utf8','gb2312');
	    $arr[] = $row;
	   }
      return $arr;
    }
 
 
 
 } 















?>
