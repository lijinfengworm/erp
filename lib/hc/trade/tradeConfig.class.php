<?php

class tradeConfig {
    
    private static $domain = 'http://www.shihuo.cn';       //voice域名
    private static $appname = 'trade';                       //新声appname
    
    /*
     * 返回域名
     */
    public static function getDomain(){
        return self::$domain;
    }
    
    /*
     * 返回cdn域名
     */
    public static function getCDNDomain($c = 1){
        $c = $c%2 ? 1 : 2;
        return 'http://c'.$c.'.hoopchina.com.cn';
    }
    
    /*
     * 根据app返回域名
     * 为voice域名，则返回空
     */
    public static function getDomainBaseOnApp(){
        return self::getCurrentApp() == self::$appname ? '' : self::$domain;
    }
    
}

?>
