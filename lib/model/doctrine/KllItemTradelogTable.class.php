<?php

/**
 * KllItemTradelogTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllItemTradelogTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllItemTradelogTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllItemTradelog');
    }


    public static function getLog($product_id=0,$offset,$limit)
    {
        $data = self::getInstance()->createQuery()
            ->select('username,attr,num,created_time')
            ->where('product_id = ?',$product_id)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('created_time desc')
            ->fetchArray();

        if(empty($data)) return array();
        foreach($data as $k=>$v)
        {
            $data[$k]['created_time'] = date('Y-m-d H:i:s',$v['created_time']);
            if(empty($v['attr']))
            {
                $data[$k]['attr'] = '无';
            }
        }
        return $data;
    }

    public static function getLogCount($product_id=0)
    {
        return self::getInstance()->createQuery()->where('product_id = ?',$product_id) ->count();
    }
}