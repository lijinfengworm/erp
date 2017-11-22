<?php

class tradeCache {

    private $key_pre = 'sf_trade_';

    function __construct() {
        $this->cache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
    }

    public function get($key) {
        return @unserialize($this->cache->get($key));
    }

    public function set($key, $value, $expire=null) {
        if($expire===null){
            $this->cache->set($key, serialize($value));
        }else{
            $this->cache->set($key, serialize($value), 0, $expire);
        }
    }
    
    public function delete($key){
        return $this->cache->delete($key);
    }

    public function getKeyPre() {
        return $this->key_pre;
    }
    
    public function increment($key, $num) {
        return $this->cache->increment($key, $num);
    }

}
