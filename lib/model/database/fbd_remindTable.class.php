<?php

/*
 * 动态
 */

class fbd_remindTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new fbd_remindTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hc_friendevent');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    /*
     * param: uid int 用户ID
     * $type: string 1,2
     * return: array 
     */

    public static function getRemindMessages($uid, $types = null) {
        $sql = 'select * from fbd_remind WHERE uid = ' . $uid . ' and `read` = 0 AND (`num` > 0 OR `recnum` > 0) ';
        if ($types != null) {
            $sql .= ' AND type in (' . $types . ')';
        }
        $sql .= ' ORDER BY dateline DESC limit 20';
        $rs = self::query($sql);
        if (!$rs) {
            return array();
        }
        $tmp = array();
        while ($row = mysql_fetch_assoc($rs)) {
            if (strpos($row['url'], 'http://bbs') !== false || strpos($row['url'], 'hupu.com/bbs') !== false) {              //过滤，暂时只需要跟帖子有关的数据
                $tmp[] = $row;
            }
        }
        return $tmp;
    }

    public static function hasRemind($uid, $types = null) {
        $sql = 'select count(*) as num from fbd_remind WHERE uid = ' . $uid . ' and `read` = 0 AND (`num` > 0 OR `recnum` > 0) and url like "http://bbs%" ';
        if ($types != null) {
            $sql .= ' AND type in (' . $types . ')';
        }
        $rs = self::query($sql);
        if (!$rs) {
            return 0;
        }
        $row = mysql_fetch_assoc($rs);
        return $row['num'];
    }

    public static function removeRemind($id, $recnum, $type = null) {
        if ($type < 8 && $type !== null) {
            $sql = 'UPDATE IGNORE fbd_remind SET num=0, recnum=0 WHERE id=' . $id;
        } else {
            $sql = 'DELETE FROM fbd_remind WHERE id =' . $id;
        }
        self::query($sql);
        if ($recnum > 0) {
            self::query('DELETE FROM fbd_recommend_list WHERE id=' . $id);
        }
    }

    /*
     * @某人
     * uid 操作的人的ID
     * authorid 帖子作者的id 
     */

    public static function atUser($content, $cid, $type, $title, $url,  $uid = 0, $authorid = 0) {
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

        $content = strip_tags($content);
        $title = str_replace('<br />', ' ', $title);
        $title = mb_convert_encoding($title, 'GBK', 'utf-8');
        $content = strtr($content, $specialReplace);
        preg_match_all("/@([\w\.\-" . chr(0x80) . "-" . chr(0xff) . "]+)/s", $content, $matches);

        $specialReplaceRevert = array(
            ' hoooop--maohao-chiiiina' => '：',
            ' hoooop--fenhao-chiiiina' => '；',
            ' hoooop--douhao-chiiiina' => '，',
            ' hoooop--juhao-chiiiina' => '。',
            ' hoooop--gantanhao-chiiiina' => '！',
            ' hoooop--wenhao-chiiiina' => '？',
            ' hoooop--shenglvehao-chiiiina' => '⋯⋯',
            ' hoooop--dunhao-chiiiina' => '、',
            ' hoooop--kongge-chiiiina' => '　',
            ' hoooop--danyinhaozuo-chiiiina' => '‘',
            ' hoooop--danyinhaoyou-chiiiina' => '’',
            ' hoooop--shuangyinhaozuo-chiiiina' => '“',
            ' hoooop--shuangyinhaoyou-chiiiina' => '”',
        );
        
        if (isset($matches[1]) && is_array($matches[1])) {
            foreach ($matches[1] as $atUserName) {
                $atUserName = strtr($atUserName, $specialReplaceRevert);
                if (!empty($atUserName) && strpos(strtolower($atUserName), '.com') < 1) {
                    $atUserName = iconv('utf-8', 'GBK', $atUserName);
                    $r = fbd_personTable::getInstance()->query('SELECT SQL_CACHE uid, username FROM fbd_person WHERE username="' . $atUserName . '" limit 1');
                    $atUser = mysql_fetch_assoc($r);
                    if (!empty($atUser['uid']) && $atUser['uid'] != $uid && $atUser['uid'] != $authorid && strtolower($atUserName) == strtolower($atUser['username'])) {
                        
                        $checkIfBlock = fbd_personTable::getInstance()->query('SELECT SQL_CACHE * FROM fbd_buddys WHERE uid=' . $atUser['uid'] . ' AND buddyid=' . $uid . ' AND level=0 limit 1');
                        $checkIfBlock = mysql_fetch_assoc($checkIfBlock);
                        // 写入提醒
                        
                        if (empty($checkIfBlock)) {
                            $minirs['uid'] = $atUser['uid'];
                            $minirs['cid'] = $cid;
                            $minirs['active'] = 2;
                            $minirs['num'] = 1;
                            $minirs['recnum'] = 0;
                            $minirs['title'] = strip_tags(str_replace(array("'", '[quote]', '[/quote]', '[url]', '[/url]', '[b]', '[/b]'), array('’', '', '', '', '', '', ''), $title));
                            $minirs['title'] = common::utf_substr($minirs['title'], 90);
                            $minirs['read'] = 0;
                            $minirs['request'] = 0;
                            $minirs['url'] = $url;
                            $minirs['type'] = $type;
                            self::insertRemind($minirs);
                        }
                    }
                }
            }
        }
    }

    public static function insertRemind($remindArray) {
        $status = self::checkRemind($remindArray['cid'], $remindArray['type'], $remindArray['uid'], $remindArray['url']);
        if ($status) {
            if (!empty($remindArray['num'])) {
                self::query('update IGNORE fbd_remind set num = num + 1, url = "'.$remindArray['url'].'", dateline = "'. $_SERVER['REQUEST_TIME'].'" where id = '.$status['id']);
            } else {
//                if (!empty($remindArray['recnum']))
//                    $this->recommendStatus = $this->checkRecommend($id); //Boolean 
//                if (empty($this->recommendStatus))
//                    $this->updateRemindNum($id, 'recnum');
            }
        }
        else {
            $_SERVER['REQUEST_TIME'] = !empty($remindArray['dateline']) ? $remindArray['dateline'] : time();
            $sql = '	INSERT IGNORE INTO fbd_remind				(
					`cid`,
					`uid`, 
					`type`, 
					`active`,
					`num`,
					`recnum`,
					`title`, 
					`url`,
					`read`,
					`request`, 
					`dateline`
				) 
				VALUES 
				(
					"' . $remindArray['cid'] . '",
					"' . $remindArray['uid'] . '",
					"' . $remindArray['type'] . '",
					"' . $remindArray['active'] . '",
					"' . $remindArray['num'] . '",
					"' . $remindArray['recnum'] . '",
					"' . $remindArray['title'] . '",
					"' . $remindArray['url'] . '",
					"' . $remindArray['read'] . '",
					"' . $remindArray['request'] . '",
					"' . $_SERVER['REQUEST_TIME'] . '"
				)';

             self::query($sql);         
        }
       
        //Update recommend list
//        if ((!empty($remindArray['recnum'])) && empty($this->recommendStatus)) {
//            $this->updateRecommendList($id, 'insert');
//        }

//        $this->updateRedmindTORedis($remindArray['uid']);
    }
    
    
    public static function checkRemind($cid, $type,$uid,$url){
        $rs = self::query('select id, num from fbd_remind where cid = '.$cid .' and type= '.$type .' and uid ='.$uid);
        if(!$rs){
            return false;
        }
        $result = mysql_fetch_assoc($rs);
        return $result ? $result : false;
    }

}
