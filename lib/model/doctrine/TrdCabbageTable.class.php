<?php

/**
 * TrdCabbageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdCabbageTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdCabbageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdCabbage');
    }
    public static function myListQuery() {
        $query = self::getInstance()->createQuery('m')
                ->where('m.is_delete = 0')
                ->orderBy('m.created_at desc');
        return $query;
    }
    /*
     * 分页调取白菜价
     */
    public static function getCabbageListToApp($page = 1,$page_size = 10){
        $offset = ($page - 1) * $page_size;
        $query =  self::getInstance()->createQuery('t')
                ->where('t.is_delete = 0')
                ->offset($offset)
                ->limit($page_size)
                ->orderBy('t.created_at desc');
        return  $query->execute();
    }
}