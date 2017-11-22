<?php 
class voicePkDataManage{
    const RED = 'a';
    const BLUE = 'b';
    
    private static $logs = array();
    function __construct(){
      //这里可进行一些初始工作
    }
    
    public static function getInstance(){
        return sfContext::getInstance()->getDatabaseConnection('voiceTtserver');
    }
    
    public static function setData($pk_id,$data){
        $key = 'voice_pk'. $pk_id;
        self::getInstance()->set($key,$data);
    }
    
    public static function getLogs($pk_id){
        $key = 'voice_pk'. $pk_id;
        self::$logs = unserialize(self::getInstance()->get($key));
        return self::$logs;
    }
    
    public static function getLog($pk_id, $uid){
        $logs = self::getLogs($pk_id);
        return isset($logs[$uid]) ? $logs[$uid] : null;
    }
    
    public static function canDo($pk_id, $uid, $party){
        $log = self::getLog($pk_id, $uid);
        if($log != null && $log != $party) return false;
        if($log == null){
            $merge_date = (array)self::$logs + array((string)$uid=>$party);
            self::setData($pk_id,$merge_date);
        }
        return true;
    }
}