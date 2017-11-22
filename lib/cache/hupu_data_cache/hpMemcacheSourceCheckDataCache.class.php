<?php

class hpMemcacheSourceCheckDataCache extends hpSourceCheckDataCache{
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
     * 保存缓存数据
     */
    public function set($key, $value){
        $this->memcache->set($this->getValueKey($key), serialize($value), 0, 0);
    }

    /*
     * 获取数据
     * $default: 失败时返回的默认数据
     */
    public function get($key, $default = null){
        return @unserialize($this->memcache->get($this->getValueKey($key)));
    }
}
