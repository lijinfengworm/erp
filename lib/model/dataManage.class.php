<?php 
class dataManage{
    protected static $instance;
    
    function __construct($key) {
        $this->key = 'sf_data_manage_'.$key;
        self::$instance = sfContext::getInstance()->getDatabaseConnection('myTTDatabase');
    }
    
    public function getData(){  
        $data = self::$instance->get($this->key);
        if(!$data){
            $cache =  new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir')));
            $data = $cache->get($this->key);
        }
        if(!$data) return array();  
        $data = unserialize($data);        
        return $data;
    }
    
    
    public function setData($data){
        $data = serialize($data);
        self::$instance->set($this->key, $data);
        $cache =  new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir')));
        $cache->set($this->key, $data);
    }
}