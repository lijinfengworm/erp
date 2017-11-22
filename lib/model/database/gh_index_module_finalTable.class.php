<?php
//手机Goalhi首页
class gh_index_module_finalTable {
    protected $db_connection;
    protected static $instance;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new gh_index_module_finalTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('new_www_gh');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        } 

        return self::$instance;
    }

    private static function query($sql) {
        return  mysql_query($sql, self::$instance->db_connection);
    }

    public static function itemByModule($name, $limit = 1) { 
        $order = "`sin` asc";
        $rs = self::query('select * from `index_module_final` WHERE `module` = "'.$name.'" order by '.$order.' limit '.$limit);

        if(!$rs){
            return null;
        }else{
            $tmp = array();
            while($row = mysql_fetch_assoc($rs)){
                $tmp[] = $row;
            }

            foreach ( $tmp as &$r ) {
                $value = unserialize ( stripslashes ( $r ['value'] ) );
                unset ( $r ['value'] );
                $r = array_merge ( $value, $r );
            }

            if($limit == 1) {
                return $tmp[0];
            }

            return $tmp;
        }
    }
}
