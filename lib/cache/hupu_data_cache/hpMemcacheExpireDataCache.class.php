<?php

/*
 * memcache 实现hpExpireDataCache
 */

class hpMemcacheExpireDataCache extends hpExpireDataCache {
   
    public function initialize($options = array()) {
        parent::initialize($options);

        if (!class_exists('Memcache')) {
            throw new sfInitializationException('You must have memcache installed and enabled to use sfMemcacheCache class.');
        }

        if ($this->getOption('memcache')) {
            $this->memcache = $this->getOption('memcache');
        } else {           
            if ($this->getOption('connection')) {
                $this->memcache = sfContext::getInstance()->getDatabaseConnection($this->getOption('connection'));
            } else {
                throw new sfInitializationException('You must give a memcache connection!');
            }
        }
    }
    
    /*
     * 获取保存数据键的键名
     */
    private function getValueKey($key){
        return $this->getOption('prefix').$key;
    }
    /*
     * 获取保存过期时间键的键名
     */
    private function getLifetimeKey($key){
        return $this->getOption('prefix').$key.'_expired_at';
    }
    


    /*
     * 保存缓存数据
     */
    public function set($key, $value, $lifetime = null){
        $lifetime = null === $lifetime ? $this->getOption('lifetime') : $lifetime;
        $this->memcache->set($this->getValueKey($key), serialize($value));
        $this->setLifetime($key, $lifetime);
//        $this->memcache->set($this->getLifetimeKey($key), time()+$lifetime);
    }
    
    public function setLifetime($key, $lifetime){
        $this->memcache->set($this->getLifetimeKey($key), time()+$lifetime);
    }

    /*
     * 获取数据
     * $default: 失败时返回的默认数据
     */
    public function get($key, $default = null){
        return @unserialize($this->memcache->get($this->getValueKey($key)));
    }
    
    public function delete($key){
        $this->memcache->delete($key);
    }
    
    /*
     * 是否过期
     * 是:返回true，否:返回false 
     * 不存在该key也返回true
     */
    public function isExpired($key){
        $expired_at = $this->memcache->get($this->getLifetimeKey($key));
        return time() > (int) $expired_at ? true : false;
    }
    
//    /*
//     * 获取源数据
//     */
//    public function getSourceData();
//    
//    /*
//     * 源数据是否合法
//     */
//    public function sourceDataIsValid($sourcedata);
//    
//    /*
//     * 更新缓存数据 
//     */
//    public function update($key, $data){
//       
//    }

}

