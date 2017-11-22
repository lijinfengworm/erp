<?php
//手机Goalhi首页
class gh_index_module_seqTable {
    protected $db_connection;
    protected static $instance;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new gh_index_module_seqTable();
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
    public static function getOrderedModules() {
        $order = " ORDER BY seq ASC";
        $rs = self::query('SELECT * FROM index_module_seq ' . $order);
        if (!$rs) {
            return null;
        }else{
            $tmp = array();
            while($row = mysql_fetch_assoc($rs)){
                $tmp[] = $row['module'];
            }
        }
        return $tmp;
    }
}
