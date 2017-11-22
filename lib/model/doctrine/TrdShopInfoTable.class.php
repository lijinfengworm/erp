<?php

/**
 * TrdShopInfoTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdShopInfoTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdShopInfoTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdShopInfo');
    }

    public static function getShopById($id)
    {
        return self::getInstance()
            ->createQuery()
            ->addWhere('id = ?',$id)
            ->addWhere('status = 0')
            ->fetchOne();
    }

    public static function getShopRand($limit)
    {
        return self::getInstance()
            ->createQuery()
            ->addWhere('status = 0')
            ->limit($limit)
            ->orderBy('rand()')
            ->execute();

    }

    public static function houtaGetCount($search = array())
    {
        $query = self::getInstance()->createQuery();
        if(!empty($search['name']))
        {
            $query->addWhere('name LIKE ?', '%' . trim($search['name']) . '%');
        }
        if(!empty($search['owner_name']))
        {
            $query->addWhere('owner_name LIKE ?', '%' . trim($search['owner_name']) . '%');
        }
        if(!empty($search['shop_category_id']))
        {
            $query->addWhere('shop_category_id = ?',$search['shop_category_id'] );
        }
        if(!empty($search['charge']))
        {
            $query->addWhere('charge > 0');
        }
        return $query->count();
    }


    public static function houtaGetList($offset=0,$limit=20,$search = array())
    {
        $query = self::getInstance()->createQuery();
        if(!empty($search['name']))
        {
            $query->addWhere('name LIKE ?', '%' . trim($search['name']) . '%');
        }
        if(!empty($search['owner_name']))
        {
            $query->addWhere('owner_name LIKE ?', '%' . trim($search['owner_name']) . '%');
        }
        if(!empty($search['shop_category_id']))
        {
            $query->addWhere('shop_category_id = ?',$search['shop_category_id'] );
        }
        if(!empty($search['charge']))
        {
            $query->addWhere('charge > 0');
        }
        if(!empty($search['orderby']))
        {
            $query->orderBy('charge '.$search['orderby']);
        }
        else
        {
            $query->orderBy('id desc');
        }

        $query = $query
            ->offset($offset)
            ->limit($limit);
        return $query->fetchArray();
    }





}
