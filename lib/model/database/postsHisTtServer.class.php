<?php
/*
 * 主要用于获取、修改帖子点击数
 */
class postsHits {

    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('postHits');
        }        
        return self::$instance;
    }

    public static function hitsIncrement($tid){        
        self::getInstance()->incr('thread_hit_'.$tid);        
    }
    
    public static function getHits($tid){
        $redis = self::getInstance()->get('thread_hit_'.$tid) ;
        return $redis ? $redis : 0;
    }

  
}