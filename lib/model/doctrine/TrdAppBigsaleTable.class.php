<?php

/**
 * TrdAppBigsaleTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdAppBigsaleTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdAppBigsaleTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdAppBigsale');
    }

    public static function getAllBigsaleCount()
    {
        $query = self::getInstance()->createQuery()->orderBy('id DESC');
        return $query->count();
    }

    public static function getBigsale($offset = 0, $limit = 15)
    {
        $bigsales = self::getInstance()->createQuery()
            ->orderBy('id DESC')
            ->limit($limit)
            ->offset($offset)
            ->execute();
        return $bigsales;
    }
}