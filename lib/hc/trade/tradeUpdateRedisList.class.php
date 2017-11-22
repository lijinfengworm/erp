<?php

/*
 * 识货dace统计更新redis操作
 */

class tradeUpdateRedisList {

    public static $redis_object = null; //redis对象 
    private static $key = 'trade_update_redis_list'; //redis key
    
    /*
     * 进行一些初始化工作
     */

    public function __construct($key)
    {
        $this->getRedis();
        self::$key = $key;
    }

    /*
     * 获取相关redis存储的key值
     */

    public function getKey()
    {
        return self::$key;
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

    /*
     * 进行redis-list值入队操作
     */

    public function addList($data)
    {
        return self::$redis_object->rPush($this->getKey(), serialize($data));
    }
    
    //清除以获取的存储数据
    public function clearPartDate($end_num = 1)
    {
        return self::$redis_object->lTrim($this->getKey(), $end_num, -1);
    }

    /*
     * 获取本对象redis-list中指定长度的值
     */
    public function getData($end_num = 1)
    {
        $result = self::$redis_object->lRange($this->getKey(), 0, $end_num - 1);
        return array_map('unserialize',$result);
    }
    
    //返回redis列表的长度
    public function getListLength()
    {
        return self::$redis_object->lLen($this->getKey());
    }
    
    public function clearAll()
    {
        return self::$redis_object->del($this->getKey());
    }

    //删除redis list中相同的值
    public function clearValue($key){
         return self::$redis_object->lrem($this->getKey(),serialize($key),0);
    }
}
