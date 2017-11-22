<?php

class pw_forumsextraTable {
    protected $db_connection;
    protected static $instance;
    protected static $db_creditset = array( 'rvrc' => array('Digest'=> 0, 'Post' => 0, 'Reply' => 0, 'Undigest' => 0, 'Delete' => 0, 'Deleterp' => 0), 'money' => array('Digest' => 400, 'Post' => 0, 'Reply' => 0, 'Undigest' => 400, 'Delete' => 10, 'Deleterp' => 1));

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new pw_forumsextraTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
            self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
            self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return self::$instance->db_connection->query($sql, self::$instance->db_connection);
    }

    /*
     * 根据fid返回数据库中的Creditset字段
     * return: string 或 false
     */
    public static function getCreditset($fid) {
        $rs = self::query('select creditset from pw_forumsextra where fid = ' . $fid . ' limit 1');
        if (!$rs) {
            return false;
        } else {
            $rs = mysql_fetch_assoc($rs);
            if (!$rs['creditset']) {
                return false;
            } else {
                return $rs['creditset'];
            }
        }
    }

    /*
     * 返回反序列化之后的creditset，
     * return: array
     */
    public static function getUnserializedCreditset($fid) {
        $rs = self::getCreditset($fid);
        if (!$rs) {
            return self::$db_creditset;
        } else {
            $creditset = unserialize($rs);
            $db_creditset = self::$db_creditset;
            if (is_array($creditset)) {
                foreach ($creditset as $key => $value) {
                    $value['Digest'] === '' && $creditset[$key]['Digest'] = $db_creditset[$key]['Digest'];
                    $value['Post'] === '' && $creditset[$key]['Post'] = $db_creditset[$key]['Post'];
                    $value['Reply'] === '' && $creditset[$key]['Reply'] = $db_creditset[$key]['Reply'];
                    $value['Undigest'] === '' && $creditset[$key]['Undigest'] = $db_creditset[$key]['Undigest'];
                    $value['Delete'] === '' && $creditset[$key]['Delete'] = $db_creditset[$key]['Delete'];
                    $value['Deleterp'] === '' && $creditset[$key]['Deleterp'] = $db_creditset[$key]['Deleterp'];
                }
                return $creditset;
            }else{
                return $db_creditset;
            }
        }
    }

    /*
     * 回帖后更新该表信息
     */
    public static function updateInfo($uid, $fid){
        $r = self::getUnserializedCreditset($fid);
        $addrvrc  = $r['rvrc']['Reply'];
        $addmoney = $r['money']['Reply'];
        $time = time();
        $rs = self::query('select * from pw_memberdata where uid ='.$uid .' limit 1');
        if($rs){
            $rs = mysql_fetch_assoc($rs);
            if($rs){              
                if(date('Y-m-d', $rs['lastpost']) == date('Y-m-d')){
                    self::query('UPDATE pw_memberdata SET postnum=postnum+1,todaypost=todaypost+1,lastpost=' .$time .',money=money+'.$addmoney.',rvrc=rvrc+'.$addrvrc.' WHERE uid='.$uid);
                }else{
                    self::query('UPDATE pw_memberdata SET postnum=postnum+1,todaypost=1,lastpost=' .$time .',money=money+'.$addmoney.',rvrc=rvrc+'.$addrvrc.' WHERE uid='.$uid);
                }
            }else{
                self::query('insert into pw_memberdata (postnum,todaypost,lastpost,money,rvrc) values (1, 1, '.time().', '.$addmoney.', '.$addrvrc.') ');
            }            
        }
    }
}
