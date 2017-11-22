<?php

/**
 * TrdShopTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdShopTable extends Doctrine_Table
{
    const TAOBAO = 0;
    const TMALL = 1;
    
    const STATUS_NORMAL = 0;
    const STATUS_BANNED = 1;
    const STATUS_BANNED_PERMANENT = 2;    
    
    /**
     * Returns an instance of this class.
     *
     * @return object TrdShopTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdShop');
    }
    /*
     * 根据 nick 返回 shop 不存在则从淘宝请求获取 存到数据库里面，没做缓存效率不高
     */
    public function getShopByNickName($name)
    {
        $taobao = new TaobaoUtil();
        $shop_info = $taobao->getTaobaoShop($name);
        
        $shop = $this->getShopByExternalId($shop_info->shop->sid);
        
        if(!$shop)
        {
            $shop = new TrdShop();
            $shop->setExternalId($shop_info->shop->sid);
            $shop->setLink('http://shop'.$shop_info->shop->sid.'.taobao.com');
            $shop->setName($shop_info->shop->title);
            $shop->setSrc(TrdShopTable::TAOBAO);
            $shop->setOwnerName($name);
            $shop->setStatus(TrdShopTable::STATUS_NORMAL);
            $shop->save();
        }
        return $shop;
    }
    public function getShopByExternalId($externalId)
    {
      return  self::getInstance()
                ->findOneBy('external_id', $externalId);
    }
    
    public function banShop($shopId)
    {
      return Doctrine_Query::create()
              ->update('TrdShop s')
              ->set('s.status', self::STATUS_BANNED)
              ->where('s.id = ?', (int)$shopId)
              ->execute();
    }
    
    public function unbanShop($shopId)
    {
      return Doctrine_Query::create()
              ->update('TrdShop s')
              ->set('s.status', self::STATUS_NORMAL)
              ->where('s.id = ?', (int)$shopId)
              ->execute();
    }    

    public function is_ban($shop_id) {
        $shop = $this->getShopByExternalId($shop_id);
        if($shop && $shop->getStatus() != self::STATUS_NORMAL) {
            return true;
        }

        return false;
    }

    public function countByExternalId($id) {
        $count = self::getInstance()->createQuery()
            ->where('external_id=?', $id)
            ->count();

        return $count;
    }

    public function get_type($id = "") {
        $type = "taobao";
        if(!$id) {
            return $type;
        }

        $shop = self::getInstance()
            ->findOneBy('id', $id);

        if($shop &&$shop->getSrc() == self::TMALL) {
            $type = "tmall";
        }

        return $type;
    }
}
