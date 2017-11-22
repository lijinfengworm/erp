<?php

/*
 * 识货抽奖统计更新redis操作
 */

class tradeUpdateRedisHash {

    public static $redis_object = null; //redis对象 
    
    /*
     * 进行一些初始化工作
     */

    public function __construct()
    {
        $this->getRedis();
    }


    /*
     * 设置redis对象
     */

    public function getRedis()
    {
        if (!self::$redis_object)
        {
            self::$redis_object = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        }

        return self::$redis_object;
    }

     /**
     * 保存单条内容
     * 
     * @param string $key 键值
     * @param string $details 内容
     * @param int $id 内容对应的id
     * @param int $expire 过期时间
     */
    public function _saveDetail($key,$id,$details,$expire = 2419200)
    {
        self::$redis_object->hSet($key, $id, $details);
        self::$redis_object->expire($key, $expire);
        return self::$redis_object;
    }
    
    /**
     * 获取单条内容
     * 
     * @param string $key 键值
     * @param int $id 内容对应的id
     */
    public function _getDetail($key,$id)
    {
        return self::$redis_object->hGet($key, $id);
    }
    
    /**
     * 获取所有内容
     * 
     * @param string $key 键值
     * @param int $id 内容对应的id
     */
    public function _getAllDetail($key)
    {
        return self::$redis_object->hGetAll($key);
    }
    
    /**
     * 获取单条内容
     * 
     * @param string $key 键值
     * @param int $id 内容对应的id
     */
    public function _get($key)
    {
        return self::$redis_object->get($key);
    }
    
    /**
     * 设置单条内容
     * 
     * @param string $key 键值
     * @param int $id 内容对应的id
     */
    public function _set($key,$value,$expire)
    {
        return self::$redis_object->set($key,$value,$expire);
    }
    
    /**
     * 增、减
     * 
     * @param string $key 键值
     * @param int $id 内容对应的id
     */
    public function _saveHincrby($key,$id,$step)
    {
        return self::$redis_object->hIncrBy($key, $id, $step);
    }
   
}
