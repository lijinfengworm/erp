<?php

/**
 * TrdGrouponProductTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdGrouponProductTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdGrouponProductTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdGrouponProduct');
    }

    /**按id查找**/
    public function getById($id){
        if(!$id)return false;
        return $info =  self::getInstance()
            ->createQuery()
            ->where('id = ?',$id)
            ->fetchOne();

    }
}