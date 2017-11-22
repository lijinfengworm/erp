<?php

/**
 * TrdMarketingActivityGroupTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdMarketingActivityGroupTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdMarketingActivityGroupTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdMarketingActivityGroup');
    }

    //获取所属活动
    public static function getMarketingBelongs($product_id)
    {
        if (empty($product_id)) return false;
        $time = time();
        $belongs = self::getInstance()->createQuery()->select('activity_id,item_id,stime,etime')
            ->whereIn('item_id', (array)$product_id)
            ->addwhere('stime <= ?', $time)
            ->addWhere('etime >= ?', $time)
            ->fetchArray();
        return $belongs;
    }
}