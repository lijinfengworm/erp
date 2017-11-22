<?php

/*
 * 装备首页存取信息类
 */
class zbHomePageDataBase{
   protected static $instance;
   public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('postHits');
        }        
        return self::$instance;
   }
   public static function getInfo($key){
       
       if(!empty($key))
       return unserialize(self::getInstance()->get($key)); 
   }
   public static function setInfo($key,$data)
   {
       
       if(!empty($key))
       self::getInstance()->set($key,  serialize($data)); 
   }
}
?>
