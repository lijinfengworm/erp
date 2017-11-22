<?php

/**
 * TrdUrlListTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdUrlListTable extends Doctrine_Table
{    
    const TAOBAOKE_ITEM = 0;
    const TAOBAOKE_SHOP = 1;
    
    const TAOBAO_ITEM = 2;
    const TAOBAO_SHOP = 3;    
    
    /**
     * Returns an instance of this class.
     *
     * @return object TrdUrlListTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdUrlList');
    }
    
    public function getUrl($key, $type)
    {
      return self::getInstance()
              ->createQuery()
              ->where('url_key = ?', $key)
              ->andWhere('type = ?', $type)                            
              ->fetchOne();
    }
    
    public function getTaobaoItemUrl($key)
    {
      return self::getInstance()
              ->createQuery()
              ->where('url_key = ?', $key)
              ->andWhere('url != ""')
              ->andWhereIn('type', array(self::TAOBAOKE_ITEM, self::TAOBAO_ITEM))                            
              ->fetchOne();      
    }
    
    public function getTaobaoShopUrl($key)
    {
      return self::getInstance()
              ->createQuery()
              ->where('url_key = ?', $key)
              ->andWhere('url != ""')
              ->andWhereIn('type', array(self::TAOBAOKE_SHOP, self::TAOBAO_SHOP))                            
              ->fetchOne();      
    }    
}