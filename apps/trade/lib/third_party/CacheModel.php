<?php
/**
 * @author Sam Feng <fengjie@hupu.com>
 */

class CacheModel
{
    /**
     *  获取缓存配置
     */
    public static function redis()
    {
        return sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
    }

    /**
     *  选择数据库
     */
    public static function selectDatabase($key)
    {
        if( !empty($key) )
        {
            sfContext::getInstance()->getDatabaseConnection('kaluliRedis')->select($key);
        }
        return ;
    }

     /**
     * 获取缓存 
     */
    public static function getCache()
    {   
        $key = self::_get_unique_key(new Exception(),1);  
        return  unserialize(self::redis()->get($key));
    }
    
    /**
     *  设置缓存 
     */
    public static function setCache($data,$time=60)
    {
        if( empty($data) ) return ;
        $key = self::_get_unique_key(new Exception(),1);
        self::redis()->set($key,serialize($data),$time);
    }
    
    /**
     * 生成唯一缓存 key
     */
    public static function _get_unique_key($e,$offset=0)
    {
        $data = $e->getTrace(); 
        if( !empty($data[$offset]['args']) )
        {
            # 设置下对象的值
            foreach( $data[$offset]['args'] as &$arg )
            {
                if( is_object($arg) )
                {
                    $arg = get_class($arg);
                }
            }
        }  
       
        $arary = array(
            'class'     => $data[$offset]['class'],
            'function'  => $data[$offset]['function'],
            'type'      => $data[$offset]['type'],
            'args'      => $data[$offset]['args'],
        );
         
        return $data[$offset]['class'].'_'.$data[$offset]['function'] .'_key:' . md5( serialize($arary) );  
    }
    
    public static function getKey()
    { 
        return self::_get_unique_key(new Exception(),1);
    }
    
    # 更新缓存offset
    public static function updateOffset( $key = null )
    {
        if(empty($key)) return false;

        $offset= self::redis()->get($key);
        if($offset>1000000)
        {
            $offset = self::redis()->set($key,1);
        }
        else
        {
            $offset = self::redis()->incr($key);
        }
        return $offset;
    }
    
}