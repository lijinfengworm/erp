<?php

/**
 * TrdDaigouInventoryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdDaigouInventoryTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdDaigouInventoryTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdDaigouInventory');
    }

    public static function myListQuery() {
        $query = self::getInstance()->createQuery('m')
            ->orderBy('m.created_at desc');
        return $query;
    }


    /*
    *get info by uid and id
    **/
    public static function getInfo($id, $uid){
        return self::getInstance()->createQuery()
            ->select('*')
            ->where('id = ?', $id)
            ->andWhere('hupu_uid = ?', $uid)
            ->fetchOne();
    }

    /*
    *get info by uid
    **/
    public static function getByUid($uid, $page = 1,$page_size = 8){
        return self::getInstance()->createQuery()
            ->select('hupu_uid,title,intro,id,front_pic,goods_info,goods_num,created_at')
            ->where('hupu_uid = ?', $uid)
            ->offset(($page-1)*$page_size)
            ->limit($page_size)
            ->orderby('created_at DESC')
            ->fetchArray();
    }

    /*
   *get info by ids
   **/
    public static function getByids(array $ids){
        return self::getInstance()->createQuery()
            ->select('*')
            ->whereIn('id', $ids)
            ->orderby("FIELD(`id`,".trim(join(",", $ids)).")")
            ->fetchArray();
    }

    /*
    *get info by typeid and
    **/
    public static function getByType($type_ids, $page = 1,$page_size = 8){
       return self::getInstance()
            ->createQuery()
            ->select('created_at,hupu_uid,hupu_username,title,goods_info,goods_num,intro')
            ->orderBy('created_at DESC')
            ->whereIn('type_id', $type_ids)
            ->offset(($page-1)*$page_size)
            ->limit($page_size)
            ->fetchArray();
    }
}