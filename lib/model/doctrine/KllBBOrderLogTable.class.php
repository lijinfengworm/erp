<?php

/**
 * KllBBOrderLogTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllBBOrderLogTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllBBOrderLogTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllBBOrderLog');
    }
    public function getAllByOrderNumber($order_number){
    	$info = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('order_number = ?',$order_number)
            ->orderBy('creat_time desc')
            ->fetchArray();
        if($info) return $info;
    }
}