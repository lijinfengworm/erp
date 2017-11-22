<?php

class pw_forumsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_forumsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    /*
     * 根据fid返回一个对象
     */

    public static function getPw_forumsObjectByFid($fid) {
        $rs = self::$instance->db_connection->query('select f.fid, f.fup, f.name, f.f_type, f.type, f.ifhide, f.password, f.allowvisit, d.tpost from `pw_forums` f left join pw_forumdata d on d.fid = f.fid where f.fid = ' . $fid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_object($rs);
        }
    }

    /*
     * 返回属于该板块的子版块
     */

    public static function getSubForum($fid) {
        $rs = self::$instance->db_connection->query('select `fid`, `name` from `pw_forums` where fup = ' . $fid . ' and f_type!="hidden" AND f_type!="former" AND password="" AND allowvisit=""', self::$instance->db_connection);
        $arr = array();
        $subfids = '';
        while ($item = mysql_fetch_assoc($rs)) {
            $item["name"] = mb_convert_encoding($item["name"], "UTF-8", "gb2312");
            $arr[] = $item;
            $subfids .= $subfids == '' ? $item['fid'] : ',' . $item['fid'];
        }
        if (!$subfids) return array();
        $rs = self::$instance->db_connection->query('select `fid`, `name` from `pw_forums` where fup in (' . $subfids . ') and f_type!="hidden" AND f_type!="former" AND password="" AND allowvisit=""', self::$instance->db_connection);
        while ($item = mysql_fetch_assoc($rs)) {
            $item["name"] = mb_convert_encoding($item["name"], "UTF-8", "gb2312");
            $arr[] = $item;
        }
        return $arr;
    }

    /*
     * 返回属于该板块的父版块
     */

    public static function getUpForum($fid) {
        $rs = self::$instance->db_connection->query('select `fid`, `fup`, `name` from `pw_forums` where fid = ' . $fid, self::$instance->db_connection);
        $item = mysql_fetch_assoc($rs);
        $arr = array();

        if (!empty($item)) {
            $up = self::$instance->db_connection->query('select `fid`, `name` from `pw_forums` where fid = ' . $item["fup"], self::$instance->db_connection);
            $up_f = mysql_fetch_assoc($up);
            if (!empty($up_f)) {
                $arr["name"] = mb_convert_encoding($up_f["name"], "UTF-8", "gb2312");
                $arr["fid"] = $up_f["fid"];
            }
        }

        return $arr;
    }

    /*
     * $haspassed是否已输过密码
     */

    public static function hasPermission($fid, $username, $groups, $haspassed) {
        $forum = self::getPw_forumsObjectByFid($fid);
        if (!$forum) {
            return false;
        }
        if ($forum->f_type == 'former' && $username === null) {  //游客不能访问该类型板块			
            return 0; //未登录
        }
        if ($forum->allowvisit != '') { //需要用户组权限才能访问			
            if ($username == '') {
                return 0;    //未登录
            }
            $arr_allowvisit = explode(',', trim($forum->allowvisit, ','));
            $arr_groups = explode(',', trim($groups, ','));
            foreach ($arr_groups as $v) {
                if (!in_array($v, $arr_allowvisit)) {
                    continue;
                }
                if ($forum->password == '') {
                    return $forum;  //拥有组权限，可以直接访问
                } else {    //需要密码访问
                    if ($haspassed) {
                        return $forum;
                    }
                    return 1;
                }
            }
            return 2;    //没有组权限
        } else {      //没有组权限限制
            if ($forum->password == '') {
                return $forum;   //可以直接访问
            } else {
                return 1;   //需要密码访问
            }
        }
    }

    /*
     * 加密板块的验证
     */

    public static function checkPasswordAndGroups($fid, $groups, $password) {
        $rs = self::$instance->db_connection->query('select password, allowvisit from `pw_forums` where fid = ' . $fid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        }
        $forum = mysql_fetch_assoc($rs);
        if (!$forum) {
            return false;
        }
        $arr_allowvisit = explode(',', trim($forum['allowvisit'], ','));
        $arr_groups = explode(',', trim($groups, ','));
        foreach ($arr_groups as $v) {
            if (!in_array($v, $arr_allowvisit)) {
                continue;
            }
            if ($forum['password'] == md5(mysql_real_escape_string($password))) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /*
     * 返回该板块下的帖子
     */

    public static function getName($fid) {
        $rs = self::$instance->db_connection->query('select name from `pw_forums` where fid = ' . $fid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return null;
        } else {
            $rs = mysql_fetch_assoc($rs);
            return $rs['name'];
        }
    }

    /*
     * 返回该板块下的帖子
     */

    public static function getPostsOfForum($fid, $type, $page) {
        return pw_threadsTable::getInstance()->getPostsOfForum($fid, $type, $page);
    }

    /*
     * 获取总页数
     */

    public static function getTotalPages($fid) {
        return intval(self::getTotalPosts($fid) / sfConfig::get('app_posts_number')) + 1;
    }

    /*
     * 根据板块ID获取总帖子数
     */

    public static function getTotalPosts($fid) {
        return pw_threadsTable::getInstance()->getTotalPosts($fid);
    }

    /*
     * 返回一个版块下亮了的回复
     */

    public static function getLightRepliesByFid($fid, $page = 1, $count = 20) {
        $light_replies = self::getForumsLightReplies(array('fid' => $fid, 'page' => $page, 'count' => $count));
        if ($light_replies == '-11' || $light_replies == '-12' || $light_replies == '') {
            return array();
        }
        $tids = self::getTidsForLightReplies($light_replies['data']);
        $pids = self::getPidsForLightReplies($light_replies['data']);
        $pid_lights = self::getPid_lightsForLightReplies($light_replies['data']);
        $lightReplies = array();
        foreach ($tids as $k => $v) {
            $lightReplies = array_merge($lightReplies, pw_postsTable::getInstance()->getRepliesAndThreadInfo($k, implode(',', $v)));
        }
        $lightReplies = self::order($lightReplies, array_flip($pids));
        foreach ($lightReplies as $k => &$v) {
            if(!is_array($v)){
                    unset($lightReplies[$k]);
            }else{
                $v['light'] = isset($pid_lights[$v['pid']]) ? $pid_lights[$v['pid']] : 5;
            }
        }
        $light_replies['data'] = $lightReplies;
        return $light_replies;
    }

    /*
     * 返回一个分类下亮了的回复
     */

    public static function getLightRepliesByClass_id($class_id, $page, $limit) {
        $lights = self::getForumsLightReplies(array('cid' => $class_id, 'count' => sfConfig::get('app_class_light_replies_max_page') * sfConfig::get('app_class_light_replies_number')));        
        if (is_array($lights)) {
            $tids = self::getTidsForLightReplies($lights);            
            $pids = self::getPidsForLightReplies($lights);
            $pid_lights = self::getPid_lightsForLightReplies($lights);
            $lightReplies = array();
            foreach ($tids as $k => $v) {
                $lightReplies = array_merge($lightReplies, pw_postsTable::getInstance()->getRepliesAndThreadInfo($k, implode(',', $v)));
            }
            $lightReplies = self::order($lightReplies, array_flip($pids));
            
            foreach ($lightReplies as $k => &$v) {
                if(!is_array($v)){
                    unset($lightReplies[$k]);
                }else{
                    $v['light'] = isset($pid_lights[$v['pid']]) ? $pid_lights[$v['pid']] : 5;
                }                
            }
            liangleMemcache::writeTt('class_light_replies_' . $class_id, serialize($lightReplies));
        } else {
            $lightReplies = @unserialize(liangleMemcache::readTt('class_light_replies_' . $class_id));
        }
        if(is_array($lightReplies)){
            $lightReplies = array_slice($lightReplies, (($page - 1) * $limit), $limit);
        }
        return $lightReplies;
    }

    static function getTidsForLightReplies($info) {
        $tids = array();
        foreach ($info as $k => $v) {
            $first = strpos($k, '_');
            $end = strrpos($k, '_');
            $len = $end - $first - 1;
            $tid = substr($k, ($first + 1), $len);
            $pid = substr($k, ($end + 1));
            $table = pw_postsTable::getTableNumber($tid);
            $tids[$table][] = $pid;
        }
        return $tids;
    }

    static function getPidsForLightReplies($info) {
        $pids = array();
        foreach ($info as $k => $v) {
            $end = strrpos($k, '_');
            $pid = substr($k, ($end + 1));
            $pids[] = $pid;
        }
        return $pids;
    }

    static function getPid_lightsForLightReplies($info) {
        $pids = array();
        foreach ($info as $k => $v) {
            $end = strrpos($k, '_');
            $pid = substr($k, ($end + 1));
            $pids[$pid] = $v;
        }
        return $pids;
    }

    static function order($arr, $order) {
        foreach ($arr as $v) {
            $order[$v['pid']] = $v;
        }
        return $order;
    }

    static function getFidsFromArray($fids) {
        $str = '';
        foreach ($fids as $v) {
            $str .= $str == '' ? $v['fid'] : ',' . $v['fid'];
        }
        return $str;
    }

    public static function getForumLightReplies($fids, $page, $count) {
        $result = self::getForumsLightReplies($fids, $page, $count);
        if ($result == '-11' || $result == '-12' || $result == '') {
            return array();
        }
        return unserialize($result);
    }

    public static function orderRepliesByPids($order, $arr) {
        $tmp = array();
        foreach ($arr as $k => $v) {
            $key = array_search($v['pid'], $order);
            $tmp[$key] = $v;
        }
        ksort($tmp);
        return $tmp;
    }

    /*
     * 返回多个版块下亮了的回复
     * param: $parameters array()
     * $parameters = array(
     *       'cid' => '174',
     *       'fid' => '34',
     *       'count' => '20',
     *       'page' => 1);
     */

    public static function getForumsLightReplies($parameters) {
        $api_config = sfConfig::get('app_light_replies_of_forums_api');
        $apiname = $api_config['apiname'];
        $appid = $api_config['appid'];
        $key = $api_config['key'];
        return SnsInterface::getContents($apiname, $appid, $key, $parameters);
    }

}

function my_compare_reply($a, $b) {
    return strcasecmp($b['postdate'], $a['postdate']);
}

