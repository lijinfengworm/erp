<?php
/**
 * 投注项目logo
 */
class touzhu_logo 
{
    protected $db_connection;
    protected static $instance;

    public static function getInstance() 
    {
        if (!isset(self::$instance)) {
            self::$instance = new touzhu_logo();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('touzhu');
            mysql_query('SET character_set_client = UTF8;', self::$instance->db_connection);
            mysql_query('SET character_set_results = UTF8;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function queryDb($sql)
    {
        return mysql_query($sql, self::$instance->db_connection);
    }

    public static function getImage($sql) 
    {
        $rs = self::queryDb($sql);
        if (!$rs) 
        {
            return false;
        } 
        else 
        {
            $arr = array();
            while ($row = mysql_fetch_array($rs,MYSQL_ASSOC ))
            {
                $arr[$row['tname']] = $row['logo_link'];
            }
            return $arr;
        }
    }

    public static function getQuery($sql , $isone = true) 
    {
        $rs = self::queryDb($sql);
        if (!$rs) 
        {
            return false;
        } 
        else 
        {
            $arr = array();
            while ($row = mysql_fetch_array($rs,MYSQL_ASSOC ))
            {
                $arr[] = $row;
            }

            if($isone && count($arr) === 1) $arr = $arr[0];            

            return $arr;
        }
    }

    public static function update($sql)
    {
        $rs = self::queryDb($sql);
        return $rs;
    }

    public static function insert($sql)
    {
        $rs = self::queryDb($sql);
        if($rs)
        {
            $id = self::queryDb('select last_insert_id() as last_id;');
            $id = mysql_fetch_array($id , MYSQL_ASSOC);
            return $id['last_id'];
        }
        return false;
    }
}