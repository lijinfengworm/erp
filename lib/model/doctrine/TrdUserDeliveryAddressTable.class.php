<?php

/**
 * TrdUserDeliveryAddressTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdUserDeliveryAddressTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdUserDeliveryAddressTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdUserDeliveryAddress');
    }
    
    public static function getAddressCountByHuuUid($uid) {
      return self::getInstance()
          ->createQuery('m')
          ->where('m.hupu_uid = ?',$uid)
          ->orderBy('m.id asc')
          ->count();                                                        
    }
    
    public static function getDefaultAddressByHuuUid($uid) {
      return self::getInstance()
          ->createQuery('m')
          ->where('m.hupu_uid = ?',$uid)
          ->andWhere('m.defaultflag = ?',1)
          ->orderBy('m.id asc')
          ->limit(1)
          ->fetchOne();                                                        
    }
    
    public static function getInfoByHupuUid($uid) {
      return self::getInstance()
          ->createQuery('m')
          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
          ->where('m.hupu_uid = ?',$uid)
          ->orderBy('m.defaultflag desc, m.id desc')
          ->execute();                                                        
    }

    public static function getInfoObjByHupuUid($uid) {
        return self::getInstance()
            ->createQuery('m')
            ->where('m.hupu_uid = ?',$uid)
            ->orderBy('m.id desc')
            ->execute();
    }

    public static function getInfoByUidId($uid,$id){

        return self::getInstance()
            ->createQuery('m')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->where('m.hupu_uid = ?',$uid)
            ->andWhere('m.id = ?',$id)
            ->fetchOne();
    }

    public static function getInfoObjByUidId($uid,$id){

        return self::getInstance()
            ->createQuery('m')
            ->where('m.hupu_uid = ?',$uid)
            ->andWhere('m.id = ?',$id)
            ->fetchOne();
    }


}