<?php

class tradeConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  }
  public function configureDoctrine(Doctrine_Manager $manager) {
      
        $servers = array(
            'host' => sfConfig::get('app_trade_cache_server_ip'),
            'port' => sfConfig::get('app_trade_cache_server_port'),
            'persistent' => true
        );
 
        $cacheDriver = new Doctrine_Cache_Memcache(array(
            'servers' => $servers,
            'compression' => false
            )
        );
 
        //enable Doctrine cache
        $manager = Doctrine_Manager::getInstance();
 
        $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
        //$manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 3600);
    }  
}
