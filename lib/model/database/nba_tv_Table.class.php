<?php 
 class nba_tv_Table{
  protected $db_connection;
  protected static $instance;

   public static function getInstance(){
   if(!isset(self::$instance)){
     self::$instance = new nba_tv_Table();
     self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("nba_tv_live");
     }   
    return self::$instance;
  }


      public static function getAll_HClive(){
     $year  = date("Y",time());
     $month = date("m",time());
     $day = date("d",time());
     $start_time = mktime(0,0,0,$month,$day,$year);
     $end_time = mktime(0,0,0,$month,$day+1,$year);
         $result = mysql_query("select * from tv_match_live where match_time BETWEEN ".$start_time." and ".$end_time."");
         $arr = array();
       while($row = mysql_fetch_assoc($result)){
            $arr[] = $row;
            
        }
        return $arr;
      }






}
?>
