<?php
 class hc_tv_Table{
    protected $db_connection;
    protected static $instance;

     public static function getInstance(){
       if(!isset(self::$instance)){
        self::$instance = new hc_tv_Table();
        self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("hc_video");
         }
       return self::$instance;
       }
    /*根据视频ID返回特定对象
    */

     public static function getTvByOriginalId($vid){
         $result = mysql_query("select vid,flash,cover from v_video where vid = ".$vid." and display = 1");
        if($row = mysql_fetch_assoc($result)){
            return $row;
        }else{
            return false;
        }
        
      }
      /*根据ID查找视频集
      */
      public static function getTvListByOriginalId($list_id){
       $result = mysql_query("select * from v_special where sid = ".$list_id);
       if($row = mysql_fetch_assoc($result)){
            $arr = self::getVideosByList_id($row["sid"]);
            $arr["name"]=$row["stitle"];
            return $arr;
         }else{
           return false;
         }
      
      }
      /*根据视频集ID查找视频ID
      
      */
      public static function getVideosByList_id($list_id){
        $arr = array();
        $result = mysql_query("select vid from v_specialvideo where sid = ".$list_id);
        while($row = mysql_fetch_assoc($result)){
          $arr[] = self::getTvByOriginalId($row["vid"]);
         
          }
          return $arr;
        
      
   
   
      }
      

   }




?>
