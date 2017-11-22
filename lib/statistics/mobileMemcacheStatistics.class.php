<?php
/**
 * 基于memcache的手机统计
 * @author hcsyp
 */
class mobileMemcacheStatistics {
    private static $instance = null;
    private static $key_prefix = 'sf_mobile_statistics_pv-';
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');
        }
        return self::$instance;
    }
    
    
    public static function PVStatistics(){
        $key = self::getPVKey();
        if(!self::getInstance()->get($key)){
            self::getInstance()->set($key, 1);
        }else{
            self::getInstance()->increment($key, 1);
        }
    }
    
    public static function PVStatisticsByDay($day){
        return (int) self::getInstance()->get(self::$key_prefix.date('Y-m-d', strtotime($day)));
    }
    
    public static function getKeys($keys){
        foreach($keys as &$key){
            $key = self::$key_prefix.$key;
        }
        return self::getInstance()->get($keys);       
    }


    private static function getPVKey(){
        return self::$key_prefix.date('Y-m-d');
    }
}

?>
