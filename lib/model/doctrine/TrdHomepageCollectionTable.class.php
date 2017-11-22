<?php

/**
 * TrdHomepageCollectionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdHomepageCollectionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdHomepageCollectionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdHomepageCollection');
    }

    public function getShowList($limit = 7)
    {
        return self::getInstance()->createQuery()
            ->where('status = 0')
            ->orderBy("id desc")
            ->limit($limit)
            ->execute();
    }
}