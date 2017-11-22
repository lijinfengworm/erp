<?php
/*
 * 存放用户回复过的帖子变化的信息的表
 * 我的空间中 最近回复的帖子有新回复的提醒
 */
class person_replyTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new person_replyTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoophits');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    /*
     * 回复帖子后更新记录
     * $type: 1 bbs, 2 blog
     * $postid 回复id
     */
    public static function reply($uid, $username, $tid, $type, $postid) {
        if($type == 1){     //帖子回复
            $type = 'bbs';
            $arr = pw_threadsTable::getInstance()->getSubjectAndReplies($tid);            
            $arr['author'] = mb_convert_encoding($arr['author'], 'utf-8', 'gbk');
        }
        $rs = mysql_query("select id, uid, new_replies from person_reply where title_id = ". $tid . " and title_type = '$type'",self::$instance->db_connection);
        $replies = array();
        while($reply = mysql_fetch_array($rs)){
            $replies[] =  $reply;
        }
        $now = time();
        $page = floor($arr['replies']/20) +1 ;
        if(!empty($replies)){
            $i = 0;
            foreach ($replies as $v) {
                if($v['uid'] == $uid){
                    $i = 1;
                    mysql_query("update person_reply set new_replies = 0, lastauthor = '$arr[author]', dateline = '$now', pid = $postid, page = $page where id = $v[id]", self::$instance->db_connection);
                }else{
                    if($v['new_replies'] == 0 && $type =='bbs'){
                        mysql_query("UPDATE `person_reply` SET `new_replies` = `new_replies`+1,`dateline` = '$now',`pid` = '$postid',`page` = '$page' where `id`='$v[id]'", self::$instance->db_connection);
                    }else{
                        mysql_query("UPDATE `person_reply` SET `new_replies` = `new_replies`+1,`dateline` = '$now' where `id`='$v[id]'", self::$instance->db_connection);
                    }
                }
            }
            if($arr['author'] != $username && $i == 0){
                $arr['author'] = mb_convert_encoding($arr['author'], 'gbk', 'utf-8');
                mysql_query("insert into person_reply (`uid` ,`title_type` ,`title_id` ,`title` ,`new_replies` ,`lastauthor` ,`dateline` ,`pid` ,`page` ,`display` )VALUES ($uid,'$type', $tid, '$arr[subject]', 0, '$arr[author]', '$now', $postid, $page, 1)", self::$instance->db_connection);
            }
        }else{
            if($arr['author'] != $username){
                $arr['author'] = mb_convert_encoding($arr['author'], 'gbk', 'utf-8');
                mysql_query("insert into person_reply (`uid` ,`title_type` ,`title_id` ,`title` ,`new_replies` ,`lastauthor` ,`dateline` ,`pid` ,`page` ,`display` )VALUES ($uid, '$type', $tid, '$arr[subject]', 0, '$arr[author]', '$now', $postid, $page, 1)", self::$instance->db_connection);
            }
        }        
    }

}
