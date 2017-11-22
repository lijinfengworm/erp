<?php

 class index_module_finalTable{
   protected $db_connection;
   protected static $instance;

     public static function getInstance(){
	  if(!isset(self::$instance)){
	    	self::$instance = new index_module_finalTable();
                self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('new_www_gh');
                mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
		mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
		mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
            } 

	  return self::$instance;
	 }

   private static function query($sql){
	 return  mysql_query($sql, self::$instance->db_connection);
    }

    public static function getNotes($page = 1){ 
        $rs = self::query('select value from `index_module_final` WHERE `module` = "milingguang" order by `date` desc limit '. ($page -1) * 5 . ' ,5');
        if(!$rs){
            return null;
        }else{
            $tmp = array();
            while($row = mysql_fetch_assoc($rs)){
                $tmp[] = $row['value'];
            }
            return $tmp;
        }
    }


 }
