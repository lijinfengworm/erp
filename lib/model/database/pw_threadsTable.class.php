<?php

/*
 * 获取帖子信息的相关操作
 */

class pw_threadsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_threadsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }
    
    public static function getFidByTid($tid){
        $rs = self::$instance->db_connection->query('select fid from `pw_threads` where tid = ' . $tid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            self::readPw_threadsOneTime($tid);
            $arr = mysql_fetch_assoc($rs);
            return $arr['fid'];
        }
    }

    /*
     * 根据tid返回一个对象
     */

    public static function getPw_threadsObjectByTid($tid) {
        $rs = self::$instance->db_connection->query('select th.tid, th.fid, th.authorid, th.author, th.subject, th.postdate, th.recs, th.locked, f.fid, f.name from `pw_threads` as th left join pw_forums as f on f.fid = th.fid where ifcheck = 1 and tid = ' . $tid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            self::readPw_threadsOneTime($tid);
            return mysql_fetch_object($rs);
        }
    }

    /*
     * 获取帖子的亮了、回复数
     */

    public static function getLightAndReplyByTids($tids) {
        $rs = self::$instance->db_connection->query('select tid, replies, ifmail  from `pw_threads` where tid in ( ' . $tids . ')');
        if (!$rs) {
            return array();
        } else {
            $arr = array();
            while($row = mysql_fetch_assoc($rs)){
                $arr[$row['tid']] = $row;
            }
            return $arr;
        }
    }
    /*
     * 点击数加1
     */

    public static function readPw_threadsOneTime($tid) {
        postsHits::hitsIncrement($tid);
    }

    /*
     * 根据帖子tid 返回主题、回复数、作者信息
     */

    public static function getSubjectAndReplies($tid) {
        $rs = self::$instance->db_connection->query('select subject, replies, author  from `pw_threads` where tid = ' . $tid . ' limit 1');
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }

    /*
     * 根据帖子tid返回一些基本信息，数组
     */

    public static function getPw_threadsByTid($tid) {
        $rs = self::$instance->db_connection->query('select th.tid, th.author, th.authorid, th.postdate, th.locked,  from `pw_threads` where tid = ' . $tid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }

    /*
     * 取得帖子发布时间和作者ID
     */

    public static function getPostdateAndAuthorid($tid) {
        $sql = 'select tid, authorid, postdate, subject from `pw_threads` where tid = ' . $tid . ' limit 1';
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }

    /*
     * 返回属于该板块的帖子的集合
     * 若该板块为父板块，则返回该父板块下的所有板块及子板块的推送贴的集合
     */

    public static function getPostsOfForum($fid, $type, $page = 1) {
        if ($type != 'sub') {
            $rs = self::$instance->db_connection->query('select `fid` from `pw_forums` where fup = ' . $fid, self::$instance->db_connection);
            $andWhere = ' or (fid in (';
            while ($row = mysql_fetch_object($rs)) {
                $andWhere .= $andWhere == ' or (fid in (' ? $row->fid : ', ' . $row->fid;
            }
            if ($andWhere == ' or (fid in (') {
                $andWhere = ' ';
            } else {
                $andWhere .= ') and pushtobbshome = 1)';
            }
        } else {
            $andWhere = ' ';
        }
        $sql = 'select `tid`, `authorid`, `subject`, `postdate`, `replies`, `ifmail`, locked from `pw_threads` where fid = ' . $fid . $andWhere . ' AND ifcheck=1 ORDER BY lastpost DESC ' . self::generateLimit($page);
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        while ($post = mysql_fetch_assoc($rs)) {
            $arr[] = $post;
        }
        return $arr;
    }

    /*
     * $tids = '1,3,5,6'
     */

    public static function getRepliesFromTids($tids) {
        $rs = self::$instance->db_connection->query('select tid,replies from pw_threads where tid in ( ' . $tids . ')');
        if (!$rs) {
            return false;
        } else {
            $arr = array();
            while ($row = mysql_fetch_array($rs)) {
                $arr[$row['tid']] = $row['replies'];
            }
            return $arr;
        }
    }

    /*
     * $tids = array(1,2,4);
     */

    public static function getRepliesByTids($tids) {
        $strids = implode(',', $tids);
        $rs = self::$instance->db_connection->query('select tid,replies from pw_threads where tid in ( ' . $strids . ')');
        if (!$rs) {
            return false;
        } else {
            $arr = array();
            while ($row = mysql_fetch_array($rs)) {
                $arr[$row['tid']] = $row['replies'];
            }
            return $arr;
        }
    }

    /*
     * 推荐后，recs+1
     * 不直接+1是因为 删除推荐时recs字段没有减1，为了更准确
     */

    public static function updateRecs($tid) {
        //$num = fbd_rm_allTable::getInstance()->getRecommendedNum($tid, 1);
        self::$instance->db_connection->query('update pw_threads set recs = recs + 1 where tid =' . $tid);
    }

    /*
     * 根据当前页数，构造limit语句
     */

    private static function generateLimit($page) {
        return ' limit ' . ($page - 1) * sfConfig::get('app_posts_number') . ', ' . sfConfig::get('app_posts_number');
    }

    /*
     * 返回一个帖子的信息、回复等
     */

    public static function getPost($tid, $page) {
        if ($page == 1) {
            return self::getPostInFirstPage($tid);
        }
        return self::getPostNotInFirstPage($tid, $page);
    }
    
    private static function getLightInfo($tid, $highlight=1){
        $light_api = sfConfig::get('app_light_api');
        $time = time();        
        $sign = md5(md5($light_api['appid']) . $time . $light_api['key']); 
        $params = array('a'=>'get', 'tid'=>$tid, 'showHightLights'=>$highlight);
        $result = SnsInterface::getContents($light_api['apiname'], $light_api['appid'], $light_api['key'], $params);
        if(is_numeric($result)){
            return array('highLight' => array(), 'all' => array());
        }else{
            return $result;
        }
    }

    /*
     * 获取帖子首页信息，包括帖子正文和前5楼的回复
     */

    public static function getPostInFirstPage($tid) {
        $repliesInfo = null;
        $lightedReplies = null;
        $mainInfo = self::getPostMainInfo($tid);
        if (!$mainInfo) { //若帖子信息为空或为false，则返回false
            return false;
        }
        $light = self::getLightInfo($tid, 1);
        $mainInfo['author'] = mb_convert_encoding($mainInfo['author'], 'UTF-8', 'gb2312');
        if ($mainInfo['ifmail'] > 0 and $mainInfo['replies'] >= 20) { //获取亮回复            
            $lightedReplies = pw_postsTable::getLightedReplies($tid, $light['highLight']);
        }
        if ($mainInfo['replies'] > 0) { //获取回复
            $repliesInfo = self::getPostRepliesInfo($tid, 0, sfConfig::get('app_replies_num_first_page'), $light['all']);
        }
        return array('mainInfo' => $mainInfo, 'repliesInfo' => $repliesInfo, 'lightedReplies' => $lightedReplies);
    }

    /*
     * 获取帖子非首页信息
     */

    public static function getPostNotInFirstPage($tid, $page) {
        $mainInfo = self::getPostMainInfo($tid);
        if (!$mainInfo) { //若帖子信息为空或为false，则返回false
            return false;
        }
        $repliesInfo = null;
        $lightedReplies = true;
        $light = self::getLightInfo($tid, 0);   
        $start = ($page - 2) * sfConfig::get('app_replies_num_other_pages') + sfConfig::get('app_replies_num_first_page');
        $repliesInfo = self::getPostRepliesInfo($tid, $start, sfConfig::get('app_replies_num_other_pages'), $light);
        return array('mainInfo' => $mainInfo, 'repliesInfo' => $repliesInfo, 'lightedReplies' => null);
    }

    /*
     * 获取帖子主要信息， 在帖子内容页第一页使用
     */

    public static function getPostMainInfo($tid) {
        $tmsgs_table = self::getTmsgsTable($tid);
        //投票已经换成新版，不需要在此做多余的LEFT JOIN，去掉
        if ($tid > 1813337) {
            $S_sql = ',tm.* ';
            $J_sql = 'LEFT JOIN ' . $tmsgs_table . ' tm ON t.tid=tm.tid';
        } else {
            $S_sql = ',tm.*,p.voteopts,p.pollid ';
            $J_sql = 'LEFT JOIN ' . $tmsgs_table . ' tm ON t.tid=tm.tid LEFT JOIN pw_polls p ON p.pollid=t.pollid';
        }
        $sql = 'SELECT t.tid, t.author, t.subject, t.postdate, t.hits, t.replies, t.ifmail ' . $S_sql . ' FROM pw_threads t ' . $J_sql . ' WHERE t.tid=' . $tid;
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }

    /*
     * 获取一条帖子基本信息，包括发帖时间、是否锁，可用于点亮操作
     */

    public static function getInfoWhenFight($tid) {
        $sql = 'select postdate, locked FROM pw_threads WHERE tid=' . $tid . ' limit 1';
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            $info = mysql_fetch_array($rs);
            return!$info ? false : $info;
        }
    }

    /*
     * 获取帖子回复信息
     * $start: limit中开始的数量 $num: 查询的条数
     */

    public static function getPostRepliesInfo($tid, $start, $num, $light) {
        return pw_postsTable::getInstance()->getRepliesInfo($tid, $start, $num, $light);
    }

    /*
     * 获取pw_posts表中特定tid的记录的某些字段
     */

    public static function getSomeFields($tid, $str) {
        $sql = 'select ' . $str . ' from pw_threads where tid =' . $tid . ' limit 1';
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        return mysql_fetch_assoc($rs);
    }

    /*
     * 获取帖子页数
     */

    public static function getTotalPages($tid) {
        return pw_postsTable::getTotalPages($tid);
    }

    /*
     * 回帖
     */

    public static function reply($fid, $tid, $content, $uid, $username) {
        return pw_postsTable::getInstance()->reply($fid, $tid, $content, $uid, $username);
    }

    
    public static function gh_reply($fid, $tid, $content, $uid, $username) {
        return pw_postsTable::getInstance()->gh_reply($fid, $tid, $content, $uid, $username);
    }
    
    /*
     * 引用 回复
     */
    public static function quote_reply($tid, $pid, $floor, $content, $uid, $username) {
        return pw_postsTable::getInstance()->quote_reply($tid, $pid, $floor, $content, $uid, $username);
    }
    /*
     * 引用 回复
     */
    public static function gh_quote_reply($tid, $pid, $floor, $content, $uid, $username) {
        return pw_postsTable::getInstance()->gh_quote_reply($tid, $pid, $floor, $content, $uid, $username);
    }

    /*
     * 	获得pw_tmsgs表名
     */

    private static function getTmsgsTable($tid) {
        return 'pw_tmsgs' . self::getTableNumber($tid);
    }

    /*
     * 	获得表num
     */

    private static function getTableNumber($num) {
        $table_tmsgs_tid = 15000;
        return intval($num / $table_tmsgs_tid) + 1;
    }

    /*
     * 根据板块ID获取总帖子数
     */

    public static function getTotalPosts($fid) {
        $rs = self::$instance->db_connection->query('select topic from `pw_forumdata` where fid = ' . $fid, self::$instance->db_connection);
        $num = mysql_fetch_assoc($rs);
        return $num['topic'];
    }
    /**
     * 根据用户id 和 板块id 获取 一个用户在某一个板块下面的发的主题数
     * @param type $uid
     * @param type $fid
     * @return type
     */
    public static function getTotoalPostsByUidAndFid($uid,array $fids){
        $rs = self::$instance->db_connection->query('select count(*) as num from `pw_threads` where fid in ('.  implode(',',$fids).') and authorid = '.$uid.'  ',self::$instance->db_connection);
        if (!$rs) {
            return null;
        } else {
            $rs = mysql_fetch_assoc($rs);
            return $rs['num'];
        }        
    }

}
