<?php

/*
 * 从淘宝链接处理
 */
class TaobaoUrlUtil{
    
    public function getMemcacheKey($option)
    {
        asort($option);
        return md5(serialize($option));
    }
    /**
     * 根据数组获取对应memcache 中的url
     * @param type $option
     * @return type 
     */
    public function getUrl(array $option)
    {
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        return $memcache->get($this->getMemcacheKey($option));
    }
    /**
     * 设置一个url
     * @param array $option
     * @param type $val
     * @return type 
     */
    public function setUrl(array $option,$val)
    {
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $key = $this->getMemcacheKey($option);
        $left_time = sfConfig::get('app_lefttime_taobaogotocache');
        return $memcache->set($key,$val,0,$left_time);
    }
    
    
}
?>
