<?php

/**
 * KllItemBrandTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllItemBrandTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllItemBrandTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllItemBrand');
    }
    public static function getCount() {
        return self::getInstance()->createQuery()->count();
    }

    public static function getList($offset,$limit) {
        $query = self::getInstance()->createQuery()
            ->orderBy('id desc')
            ->offset($offset)
            ->limit($limit);
        return $query->fetchArray();
    }
}