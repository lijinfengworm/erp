<?php

class tradeApiMessageManager{
   
    private static $cache = null,
            $apiDataExp = 600;

    public static function getCache(){
        if(!self::$cache){
            self::$cache = new tradeCache();
        }
        return self::$cache;
    }
    
    public static function setCacheKey($key){
        return $key . '_cache';
    }
    
    public static function setCacheValues($key_cache, $key, $values, $lifetime = null){
         $lifetime = $lifetime ? $lifetime : self::$apiDataExp;
         self::getCache()->set($key_cache, $key, $lifetime);
         self::getCache()->set($key, $values);
    }
}