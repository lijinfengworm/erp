<?php
/**
 * 持久化 redis
 * Author: 韩晓林
 * Date: 2015/5/4  10:50
 */
 Class tradeRedisPersistence{
     private static $redis;
     public function __Construct(){
         if(!self::$redis)
             self::$redis = new redis();
     }

     public function connect($host,$port){
         self::$redis->connect($host, $port);
     }

     public function select($house){
        self::$redis->select($house);
     }

     /**
     *get key
     **/
     public function get($key){
         if(!$key) return false;
         $res = self::$redis->get($key);
         if($res !== false)
             return $res;
         else
             return $this->set($key);
     }

     /**
      *set key
      **/
     public function set($key,$res = null){
         if(!$key) return false;

         $res = trdRedisPersistenceTable::setRes($key, $res);
         self::$redis->set($key, $res);

         return $res;
     }

     /**
      *exists key
      **/
     public function has($key){

         if(self::$redis->exists($key))
             return true;
         else
             return false;
     }

     /**
      *del key
      **/
     public function del($key){
         trdRedisPersistenceTable::delRes($key);
         return self::$redis->del($key);
     }

     /*
      *close
      **/
     public function close(){
         self::$redis->close();
     }
 }