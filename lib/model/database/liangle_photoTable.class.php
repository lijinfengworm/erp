<?php

class liangle_photoTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new liangle_photoTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("liangle_photo");
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    public static function getPhotoByTagAndNumber($tag, $number = 2) {
        //echo 'select id,title,titlepic,morepic,picsay from phome_ecms_photo where keyboard like "%' . $tag . '%" and checked = 1 order by lastdotime desc limit 0,' . $number;
        $result = mysql_query('select id,title,titlepic,morepic,picsay from phome_ecms_photo where keyboard like "%' . $tag . '%" and checked = 1 order by lastdotime desc limit 0,' . $number);
        
        $arr = array();
        while($row = mysql_fetch_object($result)) {
            $arr[] = $row;            
        }
        
        return $arr;
    }

}

?>
