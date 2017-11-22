<?php

class pw_membersTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_membersTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }
    private function query($sql){
        self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }

    /*
     * 根据uid返回一个对象
     */
    public static function getPw_membersObjectByTid($uid) {        
        $rs = self::$instance->db_connection->query('select uid, username from `pw_members` where uid = ' . $uid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_Object($rs);
        }
    }
    public static function getPw_membersArrayByUid($uid) {        
        $rs = self::$instance->db_connection->query('select uid, username ,gender from `pw_members` where uid = ' . $uid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }
    /*
     * 根据uid返回groupid
     */
    public static function getGroupidAndT_numByUid($uid) {
        $rs = self::$instance->db_connection->query('select groupid, t_num, groups from `pw_members` where uid = ' . $uid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            return mysql_fetch_assoc($rs);
        }
    }

    /*
     * 根据uid返回用户所在组
     */
    public static function getGroupsByUid($uid) {        
        $rs = self::$instance->db_connection->query('select groupid, groups from `pw_members` where uid = ' . $uid . ' limit 1', self::$instance->db_connection);
        if (!$rs) {
            return false;
        } else {
            $groups = mysql_fetch_assoc($rs);            
            if(!$groups) return false;
            return trim(trim($groups['groups'], ',') . ',' . $groups['groupid'], ',');
        }
    }

    /*
     * 检查用户名是否已存在
     */
    public static function getNameExist($name) {
        $rs = self::$instance->db_connection->query("select count(*) as num from `pw_members` where username = '$name'", self::$instance->db_connection);
        $rs = mysql_fetch_assoc($rs);
        if (!$rs['num']) {
            return false;
        } else {
            return true;
        }
    }

   
    /*
     * 推荐
     */
    public static function recommend($tid,$uid, $content='') {
        $configarr = sfConfig::get('app_recommend_api');
        $apiname = $configarr['apiname'];
        $appid = $configarr['appid'];
        $appkey = $configarr['key'];
        $paramarray = array('a'=>'thread', 'uid' => $uid,'tid' => $tid,'content' => $content ); 
        $result = SnsInterface::getContents($apiname, $appid, $appkey, $paramarray, 'POST');
        unset($configarr);
        return $result;
    }

    private static function updateFbd_commend_couWhenRecommend($authorid, $fid, $addnum) {
        $res_two = self::$instance->db_connection->query("SELECT count(*) as cou FROM fbd_commend_cou where uid='$authorid' and type=1 and fid='$fid'", self::$instance->db_connection);
        $res_two = mysql_fetch_assoc($res_two);
        if ($res_two['cou'] > 0) {
            self::$instance->db_connection->query("update fbd_commend_cou set num=num+{$addnum} where uid='$authorid' and type=1 and fid='$fid'", self::$instance->db_connection);
        } else {
            self::$instance->db_connection->query("insert into fbd_commend_cou(uid,num,fid,type) value('$authorid','$addnum','$fid','1')", self::$instance->db_connection);
        }
    }

    private static function updateTbd_comm_tid($cid, $fid, $postdate) {
        $res_tw = self::$instance->db_connection->query("SELECT count(*) as cou FROM fbd_comm_tid where tid='$cid' and fid='$fid' and postdate='$postdate'", self::$instance->db_connection);
        $res_tw = mysql_fetch_assoc($res_tw);
        if ($res_tw['cou'] > 0) {
            $sql = "update fbd_comm_tid set num=num+1 where tid='$cid' and fid='$fid' and postdate='$postdate'";
            self::$instance->db_connection->query($sql, self::$instance->db_connection);
        } else {
            $sql = "insert into fbd_comm_tid(fid,tid,num,postdate) value('$fid','$cid',1,'$postdate')";
            self::$instance->db_connection->query($sql, self::$instance->db_connection);
        }
    }

    /*
     * 点亮时
     */
    public static function updateInfo($authorid, $fid, $state, $groupid) {
        if ($groupid == 3 || $groupid == 4 || $groupid == 5 || $groupid == 51 || $groupid == 115) {
            $addnum = 6;
        } else {
            $addnum = 1;
        }
        $addnum = (int) $addnum;
        $addnum = $state == 1 ? $addnum : -$addnum;
        //self::getInstance()->insertIntoFbd_commend_item($authorid, $fid, $addnum);
        //self::getInstance()->updateFbd_commend_cou($authorid, $fid, $state, $addnum);
    }

    private static function insertIntoFbd_commend_item($authorid, $fid, $addnum) {
        $time = time();
        self::$instance->db_connection->query("insert into fbd_commend_item(uid,fid,num,time) values({$authorid},{$fid},{$addnum},{$time});", self::$instance->db_connection);
    }

    private static function updateFbd_commend_cou($authorid, $fid, $state, $addnum) {
        $newtype = ($state == 1) ? 9 : 10;
        $res_two = self::$instance->db_connection->query("SELECT count(*) as cou FROM fbd_commend_cou where uid='$authorid' and type='$newtype' and fid='$fid'", self::$instance->db_connection);
        $res_two = mysql_fetch_assoc($res_two);
        if ($res_two['cou'] > 0) {
            self::$instance->db_connection->query("update fbd_commend_cou set num=num+{$addnum} where uid='$authorid' and type='$newtype' and fid='$fid'", self::$instance->db_connection);
        } else {
            self::$instance->db_connection->query("insert into fbd_commend_cou(uid,num,fid,type) value('$authorid','$addnum','$fid','$newtype')", self::$instance->db_connection);
        }
    }

    /*
     * 根据username、password返回一个'对象'，即登录验证
     */
    public static function getPw_membersByLogin($username, $password) {
        return self::checkpass($username, $password);
    }

    private static function checkpass($username, $password) {
        $username = mb_convert_encoding($username, 'gbk', 'UTF-8');
        $sql = "SELECT m.uid,m.password,m.username,m.groupid,m.groups,md.onlineip FROM pw_members m LEFT JOIN pw_memberdata md ON md.uid=m.uid WHERE username='" . mysql_real_escape_string($username) . "' limit 1";
        $rs = self::$instance->db_connection->query($sql, self::$instance->db_connection);
        if (!$rs) {
            return false; //系统错误
        } else {
            $men = mysql_fetch_assoc($rs);
            if (empty($men)) {
                return 0; //用户名不存在
            } else {
                $password = md5($password);
                $men_uid = $men['uid'];
                $men_pwd = $men['password'];
                $check_pwd = $password;
                if (strlen($men_pwd) == 16) {
                    $check_pwd = substr($password, 8, 16); /* 支持 16 位 md5截取密码 */
                }
                if ($men_pwd == $check_pwd) { //
                    if (strlen($men_pwd) == 16) {
                        self::$instance->db_connection->query("UPDATE	pw_members SET password='$password' WHERE uid='$men_uid'", self::$instance->db_connection);
                    }
                    $L_groupid = (int) $men['groupid'];
                    /* kcy加 10月29日 登陆用户数  enter_number */
                    $today = date('Y-m-d', time());
                    $sql = "SELECT count(id) as num FROM counts where type='enter_number' and time='$today'";
                    $res = self::$instance->db_connection->query($sql, self::$instance->db_connection);
                    if ($res['num'] == 1) {
                        self::$instance->db_connection->query("update counts set nums=nums+1 where type='enter_number' and time='$today'", self::$instance->db_connection);
                    } else {
                        self::$instance->db_connection->query("insert into counts(type,time,nums) values('enter_number','$today','1')", self::$instance->db_connection);
                    }
                    return $men;  //登录成功
                } else {
                    return 2; //密码错误
                }
            }
        }
    }

    /*
     * 根据uid、password返回一个'对象'，即登录验证
     */

    public static function getPw_membersByCookie($uid, $password) {
        return self::checkpass2($uid, $password);
    }

    private static function checkpass2($uid, $password) {
        $sql = "SELECT m.uid,m.password,m.username,m.groupid,m.groups,md.onlineip FROM pw_members m LEFT JOIN pw_memberdata md ON md.uid=m.uid WHERE m.uid='" . mysql_real_escape_string($uid) . "' limit 1";
        $rs = self::$instance->db_connection->query($sql);
        if (!$rs) {
            return false; //系统错误
        } else {
            $men = mysql_fetch_assoc($rs);
            if (empty($men)) {
                return 0; //用户名不存在
            } else {
                $password = md5($password);
                $men_uid = $men['uid'];
                $men_pwd = $men['password'];
                $check_pwd = $password;
                if (strlen($men_pwd) == 16) {
                    $check_pwd = substr($password, 8, 16); /* 支持 16 位 md5截取密码 */
                }
                if ($men_pwd == $check_pwd) { //
                    if (strlen($men_pwd) == 16) {
                        self::$instance->db_connection->query("UPDATE	pw_members SET password='$password' WHERE uid='$men_uid'");
                    }
                    $L_groupid = (int) $men['groupid'];
                    /* kcy加 10月29日 登陆用户数  enter_number */
                    $today = date('Y-m-d', time());
                    $sql = "SELECT count(id) as num FROM counts where type='enter_number' and time='$today'";
                    $res = self::$instance->db_connection->query($sql);
                    if ($res['num'] == 1) {
                        self::$instance->db_connection->query("update counts set nums=nums+1 where type='enter_number' and time='$today'");
                    } else {
                        self::$instance->db_connection->query("insert into counts(type,time,nums) values('enter_number','$today','1')");
                    }
                    return $men;  //登录成功
                } else {
                    return 2; //密码错误
                }
            }
        }
    }
    
    /*
     * 约战2期，根据用户所在城市向用户发送站内信
     */
    public static function setPMByLocations($where, $content){
        if($where === 1){
            $query = self::$instance->db_connection->query('select uid, username from pw_members');
        }else{
            $query = self::$instance->db_connection->query('select uid, username from pw_members where '.$where);
        }        
        if($query){       
            $admin = sfConfig::get('app_match_center_PM');
            while($row = mysql_fetch_assoc($query)){ 
                $n = mb_convert_encoding($row['username'], 'utf-8', 'gb2312');
                $q = new hcPBPm();
                $q->set_uid_to($row['uid']);
                $q->set_username_to(mb_convert_encoding($row['username'], 'utf-8', 'gb2312'));
                $q->set_uid_from($admin['id']);
                $q->set_username_from($admin['name']);
                $q->set_title($admin['name'].'给你发来的消息');
                $q->set_content($content);
                $q->set_sendtime(time());
                $q->set_isnew(1);
                $q->set_type(1);
                hcRabbitMQPublisher::getInstance('pm')->publish($q);
            }
        }
    }
    
    
    public static function sendPMByUserId($content, $users){
        $userids = implode(',', $users);
        $q = self::$instance->db_connection->query('select uid, username from pw_members where id in ('.$userids.')');
        if($q){            
            $admin = sfConfig::get('app_match_center_PM');
            $row = mysql_fetch_assoc($q);   
            $q = new hcPBPm();
            $q->set_uid_to($row['uid']);
            $q->set_username_to($row['username']);
            $q->set_uid_from($admin['id']);
            $q->set_username_from($admin['name']);
            $q->set_title($admin['name'].'给你发来的消息');
            $q->set_content($content);
            $q->set_sendtime(time());
            $q->set_isnew(1);
            $q->set_type(1);
            hcRabbitMQPublisher::getInstance('pm')->publish($q);            
        }
    }
    /*
     * 判断某个用户是否属于一些组
     */
    public static function inGroups($uid, $groups){
        $group = self::getGroupsByUid($uid);
        if (!$group) return false;
        $mygroups = explode(',', $group);  
        if (in_array(7, $mygroups)){  //全站封禁用户，自动退出
            header('Location: http://passport.hoopchina.com/logout');
            exit;
        }
        foreach ($mygroups as $v){
            if (in_array($v, $groups)){
                return true;
            }
        }
        return false;
    }
    
    public static function globleGag($userId) {
        $apiparams = sfConfig::get('app_userchatinfo');
        $apiname = $apiparams['apiname'];
        $appid = $apiparams['appid'];
        $appkey = $apiparams['appkey'];
        $time = time();
        $sign = md5(md5($appid) . $time . $appkey);
        $type = '';
        $apiurl = 'http://interface.hupu.com/' . $apiname . '?appid=' . $appid . '&time=' . $time . '&sign=' . $sign . '&uid=' . $userId . '&type=' . $type;
        $result = file_get_contents($apiurl);
        $result = unserialize($result);
        if (isset($result['commonBanned'])) {
            return true;
        } else {
            return false;
        }
    }
}
