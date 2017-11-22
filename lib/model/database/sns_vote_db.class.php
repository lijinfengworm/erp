<?php
/*
 * 新投票的表
 * votepnum投票的人数， votenum投票票数，因为有多选的投票
 */
class sns_vote_db {

    protected $db_connection;
    protected static $instance;

    /*
     * $db_connection：连接resource，当方法从task中调用时传入该参数
     */
    public static function getInstance($db_connection = null) {
        if (!isset(self::$instance)) {
            self::$instance = new sns_vote_db();
            if($db_connection == null){
                self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('snsvote');
            }else{
                self::$instance->db_connection = $db_connection;
            }
        }
        return self::$instance;
    }

    private static function  query($sql){
        return mysql_query($sql, self::$instance->db_connection);
    }
    
    /*
     * 获取投票的基本信息
     */
    public static function getVotes($id) {
        $query = self::query('select title,voteid,votepnum,votetext from fdb_enewsvote where voteid= ' . $id . ' limit 1;');
        if ($query != false) {
            $arr = mysql_fetch_assoc($query);
            if(!$arr){
                return false;
            }
            return $arr;
        } else {
            return false;
        }
    }
    /*
     * 1：投过票 2：投票不存在 false:出错 true:成功
     */
    public static function vote($voteid, $uid, $username, $selecter){
        if(!fdb_votenewsTable::getInstance()->ifHasVoted($voteid, $uid)){
            $rs = self::query('select votetext from fdb_enewsvote where voteid = '. $voteid);
            if($rs){
                $rs = mysql_fetch_assoc($rs);
                if(!$rs){
                    return 2;
                }else{
                    $arr = explode("\r\n", $rs['votetext']);
                    $temp = array();
                    foreach($arr as $v){
                        $temp[] = explode('::::::', $v);
                    }
                    $temp[$selecter-1][1] += 1;
                    $arr = array();
                    foreach ($temp as $key => $v) {
                        $arr[] = $v[0].'::::::'.$v[1];
                    }
                    $votetext = implode($arr, "\r\n");
                    $r1 = self::query("update fdb_enewsvote set votepnum = votepnum + 1, votenum = votenum +1, votetext = '$votetext' where voteid = ".$voteid);
                    $time = time();
                    $ip = cdn2clientip::getIp();
                    $r2 = self::query("insert into  fdb_votenews (voteid, uid, uname, time, value, ip) values ( $voteid, $uid, '$username',$time, $selecter, '$ip')");
                    if($r2 || $r1){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }else{
            return 1;
        }
    }

}

?>
