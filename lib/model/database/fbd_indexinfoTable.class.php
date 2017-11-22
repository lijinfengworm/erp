<?php

/*
 * 存放“我的板块”
 */

class fbd_indexinfoTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fbd_indexinfoTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }
   

    /*
     * 获得我的板块
     */

    public static function getMyForums($uid){
        $r = self::$instance->db_connection->query('select setting from fbd_indexinfo where uid= ' . $uid, self::$instance->db_connection);
        if (!$r) {
            return null;
        } else {
            $r = mysql_fetch_assoc($r);
            if ($r == null) {
                return null;
            }            
            $r = unserialize($r['setting']);
           
            if (empty($r['myforums'])) {
                return false;
            }
            
            $newmyforums = array_unique($r['myforums']); //移除相同的值
            
            if ($r['myforums'] != $newmyforums) {
                $r['myforums'] = $newmyforums;
                $setting = serialize($newmyforums);
                self::$instance->db_connection->query("UPDATE fbd_indexinfo SET setting='$setting' WHERE uid='$uid';", self::$instance->db_connection);
            }
            $in = '(';
            foreach ($r['myforums'] as $v) {
                $in .= $in == '(' ? $v : ', ' . $v;
            }
            $in .= ')';
            $rs = self::$instance->db_connection->query('select fid, name from pw_forums where fid in ' . $in, self::$instance->db_connection);
            while ($row = mysql_fetch_assoc($rs)) {
                $row['name'] = html_entity_decode(mb_convert_encoding($row['name'], "UTF-8", "gbk"));
                $myforums[] = $row;
            }
            
            foreach ($newmyforums as $k1 => $v1) {
                foreach ($myforums as $k2 => $v2) {
                    if ($v2['fid'] == $v1) {
                        $newmyforums[$k1] = $v2;
                        break;
                    }
                }
            }
            return $newmyforums;
        }
    }

    /*
     * 获取myforums的id
     */
    public static function getMyForumsFids($uid){
        $r = self::$instance->db_connection->query('select setting from fbd_indexinfo where uid= ' . $uid, self::$instance->db_connection);
        if (!$r) {
            return null;
        } else {
            $r = mysql_fetch_assoc($r);            
            if ($r == null) {
                return null;
            }
            $setting = unserialize($r['setting']);            
            return isset($setting['myforums']) ? $setting['myforums'] : array();
        }
    }
    /*
     * 获取setting
     */
    public static function getSetting($uid){
        $r = self::$instance->db_connection->query('select setting from fbd_indexinfo where uid= ' . $uid, self::$instance->db_connection);
        if (!$r) {
            return null;
        } else {
            $r = mysql_fetch_assoc($r);
            if ($r == null) {
                return null;
            }
            $setting = unserialize($r['setting']);
            return $setting;
        }
    }

    /*
     *
     */
    public static function doWithMyForum($fid, $uid, $action){
        if($action == 'add'){
            self::getInstance()->addMyForum($fid, $uid);
        }
        if($action == 'cancel'){
            self::getInstance()->cancelMyForum($fid, $uid);
        }
    }

    /*
     * 板块收藏
     */
    public static function subscribe($fid, $uid){
        $setting = self::getInstance()->getSetting($uid);
        if(!$setting['myforums']){
            $setting['myforums'][0] = $fid;         
        }elseif(!in_array($fid, $setting['myforums'])){            
            array_push($setting['myforums'], $fid);
        }
        $setting = serialize($setting);   
        self::$instance->db_connection->query("update fbd_indexinfo set setting = '". $setting . "' where uid = ". $uid, self::$instance->db_connection);
    }
    
    /*
     * 取消收藏
     */
    public static function unsubscribe($fid, $uid){
        $setting = self::getInstance()->getSetting($uid);
        if(is_array($setting['myforums']) && in_array($fid, $setting['myforums'])){
            $setting['myforums'] = array_diff($setting['myforums'], array('0'=>$fid));
            $setting = serialize($setting);
            self::$instance->db_connection->query("update fbd_indexinfo set setting = '". $setting . "' where uid = ". $uid, self::$instance->db_connection);
        } 
    }

}

