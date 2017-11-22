<?php

/*
 * 获得用户最喜欢球队信息
 */

class fbd_personTable {

    protected $db_connection;
    protected static $instance;

    /**
     * Returns an instance of this class.
     *
     * @return object pw_postsTable
     */
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new fbd_personTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }

        return self::$instance;
    }
    
    public static function  query($sql){
        return self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }
    
    public function getFavorTeamByUid($uid) {

        mysql_select_db('hoopchina');
        $teamId     =   array(0, 31);
        $query      =   mysql_query('select favor from fbd_person where uid= ' . $uid);
        $favorTeam  =   31;   
        if ($query !== false) {
            $result =   mysql_fetch_assoc($query);
            $favorTeam  =   in_array($result['favor'], $teamId) ? 31: $result['favor'];
        }
        return $favorTeam;
    }
    
    public function getFavorTeam($uid){
        mysql_select_db('hoopchina');
        $query = mysql_query('select favor, cba,  laliga as xijia, seriea as yijia, bundesliga as dejia, ligue1 as fajia, epl as yinchao from fbd_person where uid= ' . $uid. ' limit 1');
        if($query){
            return mysql_fetch_assoc($query);
        }
        return array('favor' => 0, 'cba' => 0);
    }
    
    public function getUserDomain($uid){
        mysql_select_db('hoopchina');
        $query = mysql_query('select domain from fbd_person where uid= ' . $uid. ' limit 1');
        if($query){
            $arr = mysql_fetch_assoc($query);
            return $arr['domain'];
        }
        return false;
    }
    
    public function getUserInfoByUid($uid){
        $rs = self::$instance->db_connection->query('select uid, username ,sex,header from `fbd_person` where uid = ' . $uid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            $info = mysql_fetch_assoc($rs);
            $info['username'] = iconv('gbk', 'UTF-8//IGNORE', $info['username']);
            $header = explode('#', $info['header']);
            foreach ($header as $key=>&$val){
                $val = 'http://i1.hoopchina.com.cn/user/'.$val;
            }
            if(empty($header[1]))
            {
                $info['header'] = 'http://i1.hoopchina.com.cn/user/default_big.jpg';
            }else{
                $info['header'] = $header[1];
            }
            if(empty($header[2]))
            {
                $info['header_small'] = 'http://i1.hoopchina.com.cn/user/default_small.jpg';
            }else{
                $info['header_small'] = $header[2];
            }
            return $info;
        }
    }

    public function getUsersWithFavor($uid = 0, $limit = 1000) {
        $rs = array();
        $sql = "select uid, username, favor, cba,  laliga, seriea, bundesliga, ligue1, epl from fbd_person where  uid > $uid and favor !=31 and (favor != 0 or cba != 0  or laliga != 0 or seriea != 0 or bundesliga != 0 or ligue1 != 0 or epl != 0)  order by uid asc limit $limit";

        $query = self::$instance->db_connection->query($sql);

//        print_r("select uid, username, favor, cba,  laliga, seriea, bundesliga, ligue1, epl from fbd_person where favor != 0 or cba != 0  or laliga != 0 or seriea != 0 or bundesliga != 0 or ligue1 != 0 or epl != 0 limit $start, $limit");
        while ($row = mysql_fetch_assoc($query)) {
            $rs[] = $row;
        }

        return $rs;
    }
    
    public function getSomeUsersInterest($startUid = 0,$limit = 1000)
    {
        $rs  = array();
        $sql = "select p.uid, p.username,d.hobby from fbd_person  p left join `fbd_person_data` d on p.uid = d.uid where p.uid > $startUid and hobby !=''  order by p.uid asc limit $limit";
        
        $query = self::$instance->db_connection->query($sql);
         while ($row = mysql_fetch_assoc($query)) {
            $rs[] = $row;
        }

        return $rs;
    }
    public function getSomeUserForum($startid,$fid,$limit = 1000)
    {
        $rs  = array();
        //$sql = "select p.uid, p.username,d.fid from  `fbd_forum_count` d  left join `fbd_person` p on p.uid = d.uid where d.id > $startid and d.fid in (".  implode(',', $fids).")   order by d.id asc limit $limit";
        $sql = "select d.id,p.uid, p.username,d.fid from  `fbd_forum_count` d  left join `fbd_person` p on p.uid = d.uid where d.id > $startid and d.fid = $fid and p.uid > 0  order by d.id asc limit $limit";
        //echo $sql;exit;
        $query = self::$instance->db_connection->query($sql);
         while ($row = mysql_fetch_assoc($query)) {
            $rs[] = $row;
        }
        return $rs;
    }
    public function getSomeThreads($fid,$starttime,$endtime)
    {
        $rs = array();
        $sql = "SELECT tid,replies  FROM `pw_threads` WHERE `fid` = $fid AND `lastpost` > $starttime and `lastpost` < $endtime ";
        
        $query = self::$instance->db_connection->query($sql);
         while ($row = mysql_fetch_assoc($query)) {
            $rs[] = $row;
        }
        return $rs;        
    }
    // pw_tmsgs 分表函数
    function getTmsgsTable($tid)
    {
        $table_tmsgs_tid = 15000;
        $n = intval($tid / $table_tmsgs_tid) + 1;
        return 'pw_posts' . $n;
    }
    
    public function getThreadMsg($tid,$starttime,$endtime)
    {
        $table = $this->getTmsgsTable($tid);
        
        $sql = "SELECT fid,tid,author,authorid,postdate FROM `$table` WHERE  `postdate` > $starttime and `postdate` < $endtime and `tid` = $tid  ";
        //echo $sql;
        $query = self::$instance->db_connection->query($sql);
        $rs = array();
        while ($row = mysql_fetch_assoc($query)) {
            $rs[] = $row;
        }
        return $rs;        
    }
}
