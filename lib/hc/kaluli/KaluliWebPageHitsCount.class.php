<?php
  /*
   * 更新页面的浏览数
   * 做缓存更新避免每次的频繁数据库表的更新操作
   * 其他页面可仿效做方法的扩展进行实现
   */  
    class KaluliWebPageHitsCount{
        private static $redisHandle = null;
        private static $updateTime = 900;    #更新时间
        private static $cacheTime = 2592000; #缓存时间

        public function __construct() {
            self::$redisHandle = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            self::$redisHandle->select(10);
        }

        public static function getInstance(){
            return new KaluliWebPageHitsCount();
        }
        
        //新声msg内页点击数(浏览数)统计
        public function kaluliMsgHitsCount($msg_id,$action,$updateTime = 0){
            if(!$msg_id || !$action) return false;

            #key
            $hit_count_latest_time_key = 'kaluli.hits.time.action.'.$action.'.id.'. $msg_id;
            $hit_count_key = 'shihuo.hits.count.action.'.$action.'.id.'. $msg_id;
            self::$updateTime = $updateTime ? $updateTime : self::$updateTime;

            $latest_hit_count_time = self::$redisHandle->get($hit_count_latest_time_key);
            $hit_num = (int)self::$redisHandle->get($hit_count_key);

            #更新操作
            if($latest_hit_count_time){#存在数值
                if (((time() - (int)$latest_hit_count_time) >= self::$updateTime) && $hit_num){     //更新周期 15m
                    self::rewriteDb($msg_id,$action,$hit_num);

                    self::$redisHandle->setex($hit_count_key,self::$cacheTime,0);                   //累计点击数目并设过期时间为 1m
                    self::$redisHandle->setex($hit_count_latest_time_key,self::$cacheTime,time());  //更新最新更新时间为当前时间
                } else {
                    self::$redisHandle->setex($hit_count_key,self::$cacheTime,$hit_num+1);
                }
            }else{
                self::$redisHandle->setex($hit_count_key,self::$cacheTime,1);
                self::$redisHandle->setex($hit_count_latest_time_key,self::$cacheTime,time());     //设置key值并设过期时间为 1m
            }
        }

    /*
    *回写数据库
    *
    **/
    private  static function rewriteDb($msg_id,$action,$hit_num){

        if($action == 'articleDetail'){#文章详情页

            $res = KaluliArticleTable::getInstance()->find($msg_id);
            $res->setHits((int)$res->getHits() + $hit_num);
            $res->save();

        }if($action == 'itemDetail') {#商品详情页

            $res = KaluliItemTable::getInstance()->find($msg_id);
            $res->setHits((int)$res->getHits() + $hit_num);
            $res->save();

        }
    }
        
 }
