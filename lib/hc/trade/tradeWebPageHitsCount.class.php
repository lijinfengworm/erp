<?php
  /*
   * 更新页面的浏览数
   * 做缓存更新避免每次的频繁数据库表的更新操作
   * 其他页面可仿效做方法的扩展进行实现
   */  
    class tradeWebPageHitsCount{
        private static $redisHandle = null;
        private static $updateTime = 900;    #更新时间
        private static $cacheTime = 2592000; #缓存时间

        public function __construct() {
            self::$redisHandle = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        }

        public static function getInstance(){
            return new tradeWebPageHitsCount();
        }
        
        //新声msg内页点击数(浏览数)统计
        public function tradeMsgHitsCount($msg_id, $action, $updateTime = 0){
            if(!$msg_id || !$action) return false;

            #key
            $hit_count_latest_time_key = 'shihuo_hits_time_action_'.$action.'_id_'. $msg_id;
            $hit_count_key = 'shihuo_hits_count_action_'.$action.'_id_'. $msg_id;
            self::$updateTime = $updateTime ? $updateTime : self::$updateTime;

            #值
            if($action == 'newsDetail'){#news为了兼容,特殊处理
                $hit_count_latest_time_key = 'shihuo_hits_time_msg_' . $msg_id;
                $hit_count_key = 'shihuo_hits_count_msg_' . $msg_id;
            }

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

        if($action == 'newsDetail'){#优惠详情页

            $res = TrdNewsTable::getInstance()->find($msg_id);
            $res->setHits($res->getHits() + $hit_num);
            $res->save();

        }elseif($action == 'findDetail'){#发现详情页

            $res = TrdItemAllTable::getInstance()->find($msg_id);
            $res->setClickCount($res->getClickCount() + $hit_num);
            $res->setHeat($res->getHeat() + $hit_num);
            $res->save();

        }elseif($action == 'daigouDetail'){//代购详情页

            $res = TrdProductAttrTable::getInstance()->find($msg_id);
            $res->setHits($res->getHits() + $hit_num);
            $res->setDaceHits($res->getDaceHits() + $hit_num);
            $res->save();
        }elseif($action == 'shaiwuDetail'){//晒物详情页

            $res = trdShaiwuProductTable::getInstance()->find($msg_id);
            $res->setHits($res->getHits() + $hit_num);
            $res->save();
        }elseif($action == 'special'){//晒物详情页

            $res = TrdSpecialTable::getInstance()->find($msg_id);
            $res->setClickCount($res->getClickCount() + $hit_num);
            $res->save();
        }elseif($action == 'findNewsDetail'){//新发现详情页

            $res = TrdFindTable::getInstance()->find($msg_id);
            $res->setHits($res->getHits() + $hit_num);
            $res->save();
        }elseif($action == 'goodsDetail'){//商品仓库详情页
            $res = TrdGoodsStyleTable::getInstance()->find($msg_id);
            if(!empty($res))
            {
                $res->setHits($res->getHits() + $hit_num);
                $res->save();
            }
        }


    }
        
 }
