<?php

/*
 * 获取帖子回复相关操作
 */

class pw_postsTable {

    protected $db_connection;
    protected static $instance;

    /**
     * Returns an instance of this class.
     *
     * @return object pw_postsTable
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_postsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    /*
     * 获取一条回复的基本信息
     */

    public static function getOnePostInfo($pid, $tid) {
        $sql = 'select author, authorid, postdate, content from ' . self::getPostsTable($tid) . ' where pid = ' . $pid;
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

    public static function getRepliesInfo($tid, $start, $num, $light) {
        $sql = "select pid, author, authorid, content, alterinfo,via from " . self::getPostsTable($tid) .
                            " where pid >= (select pid from " . self::getPostsTable($tid) . " where tid = ". $tid ." order by pid asc limit " . $start . ",1)
                            and tid = ".$tid." order by pid asc limit ".$num;
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);

        if (!$rs) {
            return null;
        } else {
            $tmp = array();
            while ($row = mysql_fetch_assoc($rs)) {
                $row['num'] = isset($light[$row['pid']]) ? $light[$row['pid']] : 0;
                $tmp[] = $row;
            }
            return $tmp;
        }
    }

    /*
     * 计算当前回复前的回复数量
     */

    public static function getBeforeRepilesCount($tid, $pid) {
        $sql = 'select count(pid) as c from ' . self::getPostsTable($tid) . ' where tid='. $tid . ' and pid < ' . $pid . ' limit 1';
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        if (!$rs) {
            return null;
        } else {
           $row = mysql_fetch_assoc($rs);

           return $row["c"];
        }
    }

    /*
     * 获取帖子亮回复信息
     */

    public static function getLightedReplies($tid, $highLight) {
        return ttServer::getLightedReplies($tid, $highLight);
    }

    private static function setLightInfo($tid, $fid, $pid, $uid) {
        $light_api = sfConfig::get('app_light_api');
        $time = time();
        $sign = md5(md5($light_api['appid']) . $time . $light_api['key']);
        $ip = cdn2clientip::getIp();
        $params = array('a' => 'set', 'tid' => $tid, 'fid' => $fid, 'pid' =>$pid, 'id'=>$ip, 'uid' => $uid);
        return snsInterface::getContents($light_api['apiname'], $light_api['appid'], $light_api['key'], $params);
    }

    /*
     * 	点亮回复
     */

    public static function light($tid, $pid, $uid, $fid) {
        $result = self::setLightInfo($tid, $fid, $pid, $uid);       
        if ($result < 0) {
            return false;
        }
        return true;
    }

    /*
     * 回帖
     * return: int
     * 成功返回回复的id，否则返回0
     * 回帖后相关操作：
     * 1.回帖内容的插入(pw_post)、帖子主要信息（回复数、最后回帖者、最后回帖时间等）更新（pw_threads）
     * 2.我最近回复过的帖子(my首页左上角处)（person_reply）
     * 3.在某个板块一周内回帖数的统计（fbd_cou_thread_new）
     * 4.插入 我的空间-》帖子-》回帖 数据的插入（fbd_all_reply 【192.168.1.52】）
     * 5.卡路里增减操作、我的空间-》我的帖子-》总共X贴的更新（pw_forumsextra）
     * 6.论坛每日回帖统计（counts）
     */

    public static function reply($tid, $content, $uid, $quotepid, $boardpw = NULL) {       
        $post = pw_threadsTable::getPw_threadsObjectByTid($tid);
        if (!$post || $post->locked) {
            return false;
        }
        $content = htmlentities($content, ENT_QUOTES, 'utf-8');
        $content = preg_replace("/\n/", "<br />", $content);
        $configarr = sfConfig::get('app_reply_api');
        $apiname = $configarr['apiname'];
        $appid = $configarr['appid'];
        $appkey = $configarr['key'];
        $via = $configarr['via'];        
        $paramarr = array('uid' => $uid, 'tid' => $tid, 'content' => $content, 'quotepid' => $quotepid, 'boardpw' =>md5($boardpw),'via' => $via, 'charset' => 'utf-8','ip' => cdn2clientip::getIp());
        $result = SnsInterface::getContents($apiname, $appid, $appkey, $paramarr, 'POST');
        return $result;
    }

    /*
     * @用户名 转换成链接
     */

    private static function userNameToLink($content, $escape = false) {
        $originContent = $content;
        $magicWords = 'hooorzxxoosopurooosdfooooo4ooo2oooxyz6p';
        $specialReplace = array(
            '：' => ' hoooop--maohao-chiiiina',
            '；' => ' hoooop--fenhao-chiiiina',
            '，' => ' hoooop--douhao-chiiiina',
            '。' => ' hoooop--juhao-chiiiina',
            '！' => ' hoooop--gantanhao-chiiiina',
            '？' => ' hoooop--wenhao-chiiiina',
            '⋯⋯' => ' hoooop--shenglvehao-chiiiina',
            '、' => ' hoooop--dunhao-chiiiina',
            '——' => ' hoooop--pozhehao-chiiiina',
            '　' => ' hoooop--kongge-chiiiina',
            '‘' => ' hoooop--danyinhaozuo-chiiiina',
            '’' => ' hoooop--danyinhaoyou-chiiiina',
            '“' => ' hoooop--shuangyinhaozuo-chiiiina',
            '”' => ' hoooop--shuangyinhaoyou-chiiiina',
        );
        $content = strtr($content, $specialReplace);
        $pattern = "/@([\w\.\-" . chr(0x80) . "-" . chr(0xff) . "]+)/s";
        $content = preg_replace($pattern, "@$1" . $magicWords, $content);
        preg_match_all($pattern, $content,$matches);
        
        if (isset($matches[1]) && is_array($matches[1])) {
            foreach ($matches[1] as $atUserName) {
                $realAtUserName = str_replace($magicWords, '', $atUserName);
                if (!empty($atUserName) && strpos(strtolower($realAtUserName), '.com') < 1 && strpos(strtolower($realAtUserName), '.net') < 1) {
                    $realAtUserName = iconv('utf-8', 'GBK', $realAtUserName);
                    $r = fbd_personTable::getInstance()->query('SELECT SQL_CACHE uid, username FROM fbd_person WHERE username="' . $realAtUserName . '"');
                    $atUser = mysql_fetch_assoc($r);
                    // 必须比较 $realAtUserName 和 $atUser['username'],由于字符问题会出现 @艾玛 -> @艾萌 的bug
                    if (!empty($atUser['uid']) && strtolower($realAtUserName) == strtolower($atUser['username'])) {
                        $atContent = '@<a class="u" target="_blank" href="http://my.hupu.com/'.$atUser['uid'].'">' . iconv('GBK', 'utf-8', $atUser['username']) . '</a>';
                        $escape && $atContent = mysql_escape_string($atContent);
                        // 替换时防止把 @42642 分割为 @426 42;自动纠正大小写错误
                        $content = str_replace('@' . $atUserName, $atContent, $content);
                    }
                }
            }
        }
        $content = str_replace($magicWords, '', $content);
	$specialReplaceRevert = array(
		' hoooop--maohao-chiiiina' => '：',
		' hoooop--fenhao-chiiiina' => '；',
		' hoooop--douhao-chiiiina' => '，',
		' hoooop--juhao-chiiiina' => '。',
		' hoooop--gantanhao-chiiiina' => '！',
		' hoooop--wenhao-chiiiina' => '？',
		' hoooop--shenglvehao-chiiiina' => '⋯⋯',
		' hoooop--dunhao-chiiiina' => '、',
		' hoooop--pozhehao-chiiiina' => '——',
		' hoooop--kongge-chiiiina' => '　',
		' hoooop--danyinhaozuo-chiiiina' => '‘',
		' hoooop--danyinhaoyou-chiiiina' => '’',
		' hoooop--shuangyinhaozuo-chiiiina' => '“',
		' hoooop--shuangyinhaoyou-chiiiina' => '”',
	);
        $content = strtr($content, $specialReplaceRevert);
	empty($content) && $content = $originContent;
        return mb_convert_encoding($content, "gbk", 'utf-8');
    }


    /*
     * 返回www版的页数
     */

    public static function getWWWPageByFloor($floor) {
        if ($floor <= 19) {
            return 1;
        }
        return ceil(($floor - 19) / 20) + 1;
    }

    /*
     * 投票
     */

    public static function vote($voteid, $uid, $username, $selecter, $type) {
        if ($type == 1) {
            return sns_vote_db::getInstance()->vote($voteid, $uid, $username, $selecter);
        }
        if ($type == 2) {
            return pw_pollsTable::getInstance()->vote($voteid, $username, $selecter);
        }
    }

    /*
     * 获取帖子推荐信息
     */

    public static function getRecommendedInfo($tid) {
        $num = fbd_rm_allTable::getRecommendedNum($tid, 1);
        if ($num > 1) {
            fbd_rm_allTable::getRecommendedPeople($tid);
        }
    }

    /*
     * 获取帖子页数
     */

    public static function getTotalPages($tid) {
        $num = pw_threadsTable::getSomeFields($tid, 'replies');
        return ceil(($num['replies'] - sfConfig::get('app_replies_num_first_page')) / sfConfig::get('app_replies_num_other_pages')) + 1;
    }

    /*
     * 	获得pw_posts表名
     */

    public static function getPostsTable($tid) {
        return 'pw_posts' . self::getTableNumber($tid);
    }

    /*
     * 	获得表num
     */

    public static function getTableNumber($num) {
        $table_tmsgs_tid = 15000;
        return intval($num / $table_tmsgs_tid) + 1;
    }
    
    
    public static function getRepliesAndThreadInfo($table, $pids){
        $postTable = 'pw_posts'.$table;
        $result = self::$instance->db_connection->query('select p.pid, p.fid, p.tid, p.author, p.authorid, p.postdate, p.content, t.subject from '.$postTable.' as p left join pw_threads as t on t.tid = p.tid where p.pid in ('.$pids.') order by pid desc');
        if(!$result) return array();
        $arr = array();
        while($row = mysql_fetch_assoc($result)){
            $arr[] = $row;
        }
        
        return $arr;
    }
    
}

