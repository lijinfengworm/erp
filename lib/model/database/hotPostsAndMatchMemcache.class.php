<?php
/*
 * hotPostsAndMatchMemcache操作类
 * 主要用于在首页调取热门帖子和跟比赛相关的一些信息，如每日比赛场次、球员效率等
 */
class hotPostsAndMatchMemcache {

    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('hotPostsAndMatchMemcache');
        }        
        return self::$instance;
    }

  
}