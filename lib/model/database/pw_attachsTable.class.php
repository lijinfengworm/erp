<?php

/*
 * 获取帖子回复相关操作
 */

class pw_attachsTable {

    protected $db_connection;
    protected static $instance;

    /**
     * Returns an instance of this class.
     *
     * @return object pw_postsTable
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_attachsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql){
        return self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }
    public static function getAttachments($tid){
        $sql = 'select attachurl from pw_attachs where tid = '. $tid .' and pid = 0 and type = "img"';
        $rs = self::query($sql);
        $attachments = array();
        if($rs){            
            while($row = mysql_fetch_assoc($rs)){
                $attachments[] = $row['attachurl'];
            }
        }
        return $attachments;        
    }
 

}

