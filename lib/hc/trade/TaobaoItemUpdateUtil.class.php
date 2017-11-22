<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaobaoItemUpdateUtil
 *
 * @author Administrator
 */
class TaobaoItemUpdateUtil {
    protected $connection;
    protected static $instance;
    protected static $addSubscriptionKey = 'trade_item_add_subscript_key';
    protected static $delSubscriptionKey = 'trade_item_del_subscript_key';
    protected static $updatedKey = 'trade_item_updated_key';
    /**
     * 有些地方脚本运行时间长 比如task中需要 不进行重新链接会出现连接断开的问题
     * @param type $keep_alive
     * @return type
     */
    public static function getInstance($keep_alive = FALSE) {
        if (!isset(self::$instance) || $keep_alive) {
            self::$instance = new TaobaoItemUpdateUtil();
            self::$instance->connection = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            self::$instance->connection->select(1);
        }
        return self::$instance;
    }
    //加入订阅商品列表
    public static function addSubscription($itemId){
        self::$instance->connection->lrem(self::$addSubscriptionKey,$itemId,0);
        return self::$instance->connection->rpush(self::$addSubscriptionKey,$itemId);
    }
    //获取将要订阅商品列表 取num个值 然后把取到的值删除
    public static function getAddSubscriptionPop($num){
        $key = self::$addSubscriptionKey;
        $list = self::$instance->connection->lrange($key,0,$num-1);
        self::$instance->connection->ltrim($key,$num,-1);
        return $list;
    }
    //将要取消订阅的商品列表
    public static function addDeleteSubscription($itemId){
        self::$instance->connection->lrem(self::$delSubscriptionKey,$itemId,0);
        return self::$instance->connection->rpush(self::$delSubscriptionKey,$itemId);
    }
    //获取将要取消订阅的商品列表 取num个值 然后把取到的值删除
    public static function getDeleteSubscriptionPop($num){
        $key = self::$delSubscriptionKey;
        $list = self::$instance->connection->lrange($key,0,$num-1);
        self::$instance->connection->ltrim($key,$num,-1);
        return $list;
    }
    //增加要更新的商品列表
    public static function addUpdated($itemId){
        //list存在的话先把存在的移除
        self::$instance->connection->lrem(self::$updatedKey,$itemId,0);
        return self::$instance->connection->rpush(self::$updatedKey,$itemId);
    }
    //获取要更新的商品列表 取num个值 然后把取到的值删除
    public static function getUpdatedPop($num){
        $key = self::$updatedKey;
        $list = self::$instance->connection->lrange($key,0,$num-1);
        self::$instance->connection->ltrim($key,$num,-1);
        return $list;
    }
    
    
}

?>
