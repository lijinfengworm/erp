<?php

class cba_tv_Table {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new cba_tv_Table();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection("cba_tv_live");
        }
        return self::$instance;
    }

    public static function getAll_CBASchedule($match_id) {
        $result = mysql_query("select * from hoop_cba_schedule where id =" . $match_id);
        if ($row = mysql_fetch_assoc($result)) {
            return $row;
        } else {

            return false;
        }
    }

}

?>
