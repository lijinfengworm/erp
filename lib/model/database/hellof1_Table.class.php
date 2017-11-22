<?php 
 class hellof1_Table{
       protected $db_connection;
         protected static $instance;

          public static function getInstance(){
                 if(!isset(self::$instance)){
                          self::$instance = new hellof1_Table();
                               self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("f1_tv_live");
                               }   
                     return self::$instance;
                   }


             public static function get_hellof1live(){
              $result = mysql_query("select id,title,stime,etime,playurl from tv_live  ORDER BY etime DESC LIMIT 0,10");
               $arr = array();
              while($row = mysql_fetch_assoc($result)){
                       $row["league"] = 4;
                          $arr[] = $row;
                                                             
             }
                                  return $arr;
      }


 }
?>
