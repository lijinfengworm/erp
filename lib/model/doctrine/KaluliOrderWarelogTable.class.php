<?php

/**
 * KaluliOrderWarelogTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KaluliOrderWarelogTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KaluliOrderWarelogTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KaluliOrderWarelog');
    }

    public static function myListQuery() {
        $query = self::getInstance()->createQuery('m')
            ->orderBy('m.created_at desc');
        return $query;
    }
}