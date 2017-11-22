<?php

/*
 * ttServer操作类
 */

class ttServer {

    protected static $instance;
    protected $connection;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('myTTDatabase');
        }
        return self::$instance;
    }

    /*
     * 写: tf_user_{$pid} key
     * tf_user_pid:该值记录了哪些用户点亮过该回复，pid为回复的ID
     */

    public static function writeTfUser($pid, $uid) {
        $val = unserialize(self::readTt('tf_user_' . $pid));
        if (!empty($val)) {
            if (in_array($uid, $val)) {
                return false;  //已回复
            }
        }
        if (!empty($val) && is_array($val)) { //设置值
            array_unshift($val, $uid);
        } else {
            $val = array($uid);
        }
        self::writeTt('tf_user_' . $pid, $val);   //将值写入key
        return true;
    }

    /*
     * 读取key值
     */
    public static function readTt($key) {
        return self::getInstance()->get($key);
    }
    /*
     * 写key值
     */
    public static function writeTt($key, $value) {
        self::getInstance()->set($key, $value);
    }

    /*
     * 写入支持或反对键
     * tip_tid_pid:支持（亮了）
     * fight_tid_pid：反对（亮你妹）
     */

    public static function writeTipOrFight($tid, $pid, $tidOrFight, $groupid, $uid) {
        $tidOrFight = $tidOrFight == 1 ? 'tip' : 'fight';
        $tidOrFight.= '_' . $tid . '_' . $pid;
        $val = self::getInstance()->get($tidOrFight);
        if ($groupid == 5 || $groupid == 51 || $groupid == 115) {
            $retval = 3;
        } elseif ($groupid == 3 || $groupid == 4) {
            $retval = 5;
        } else {
            $retval = 1;
        }
        if (empty($val)) { //为空则新建key
            self::getInstance()->set($tidOrFight, $retval);
        } else {
            self::getInstance()->increment($tidOrFight, $retval);
        }
        if (self::getLightNumber($tid, $pid) > 5) {
            self::writeTid_sort($tid, $pid, $uid, $retval);
        }
    }

    /*
     * 写 tid_sort_{$tid}
     */

    public static function writeTid_sort($tid, $pid, $uid, $value) {
        $tid_sort = self::readTid_sort($tid);
        $tid_sort[$pid] = intval($tid_sort[$pid]) + $value;
        self::writeTt('tid_sort_' . $tid, $tid_sort);
    }

    /*
     * 读 tid_sort_{$tid}
     */

    public static function readTid_sort($tid) {
        return unserialize(self::readTt('tid_sort_' . $tid));
    }

    /*
     * 获取点亮数
     */

    public static function getLightNumber($tid, $pid) {
        return intval(self::getInstance()->get('tip_' . $tid . '_' . $pid)) - intval(self::getInstance()->get('fight_' . $tid . '_' . $pid));
    }

    /*
     * 获取帖子亮回复信息
     */

    public static function getLightedReplies($tid, $lightPosts) {
//        $lightPosts = self::readTid_sort($tid);
//        if (empty($lightPosts)) {
//            return false;
//        }
//        arsort($lightPosts); //按次数递减排序
//        foreach ($lightPosts as $k => $v) {
//            if ($v < 5)
//                unset($lightPosts[$k]);
//        }
        while (count($lightPosts) > 10) { //长度大于10，取前10
            array_pop($lightPosts);
        }

        $table_posts = pw_postsTable::getPostsTable($tid);
        $lightPostIds = '';
        foreach ($lightPosts as $k => $v) {
            $lightPostIds .= $lightPostIds == '' ? $k : ', ' . $k;
        }
        $r = mysql_query('select pid, author, authorid, postdate, content, alterinfo, ipfrom from ' . $table_posts . ' where pid in (' . $lightPostIds . ')');
        if (!$r) {
            return null;
        }   
        $tmp = array();
        while ($row = mysql_fetch_assoc($r)) {
            $row['num'] = $lightPosts[$row['pid']];
            $tmp[] = $row;
        }
        $temp = array();
        $order = array_keys($lightPosts);
        foreach($tmp as $v){
            $temp[array_search($v['pid'], $order)]  = $v;
        }
        ksort($temp);
        return $temp;
    }

}

