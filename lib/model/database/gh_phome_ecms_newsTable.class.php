<?php
/*
 * 虎扑制造
 */
class gh_phome_ecms_newsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new gh_phome_ecms_newsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('gh_phome_ecms_news');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }


    public static function getTotalPage(){
        $rs = mysql_query('SELECT count(*) as num FROM `phome_ecms_news` WHERE `classid` = 338 and checked = 1 and havehtml =1');
        if(!$rs){
            return null;
        }else{
            $row = mysql_fetch_assoc($rs);
            return ceil($row['num']/sfConfig::get('app_column_page_size'));
        }
    }

    /*
     * m首页调用
     */
    public static function getMadeInHoop($num){
        $rs = mysql_query('SELECT title,smalltext,id  FROM `phome_ecms_news` WHERE `classid` in (455, 468, 675, 681, 740, 842, 992, 993, 998, 999, 1000, 1001, 411, 409, 410, 412, 430 ) and checked = 1 and havehtml =1 ORDER BY id desc limit '.$num);
        if(!$rs){
            return 0;
        }else{
            $data = array();
            while($row = mysql_fetch_assoc($rs)){
                $data[] = $row;
            }
            return $data;
        }
    }

    //列表
    public static function getArticlesListByPage($page){
        $rs = mysql_query('SELECT title,smalltext,id  FROM `phome_ecms_news` WHERE `classid` in (455, 468, 675, 681, 842, 992, 993, 997, 998, 999, 1000, 1001, 411, 409, 410, 412, 430 ) and checked = 1 ORDER BY `newstime` DESC limit '.($page - 1) * sfConfig::get('app_column_page_size'). ', '. sfConfig::get('app_column_page_size'));
        if(!$rs){
            return null;
        }else{
            $data = array();
            while($row = mysql_fetch_assoc($rs)){
                $data[] = $row;
            }
            return $data;
        }
    }

    //详情
    public static function getArticleById($id){
        $rs = mysql_query('SELECT title,newstext,newstime FROM phome_ecms_news WHERE id = '.$id.' limit 1');
        if(!$rs){
            return null;
        }
        return mysql_fetch_assoc($rs);
    }

    public static function getCommend() {
        $string = mb_convert_encoding("推荐", 'gbk', 'utf-8');
        $rs = mysql_query('SELECT * FROM phome_ecms_news WHERE keyboard like "%' . $string . '%" and checked = 1 order by id desc limit 1');

        if(!$rs){
            return null;
        }
        return mysql_fetch_assoc($rs);
    }
}
