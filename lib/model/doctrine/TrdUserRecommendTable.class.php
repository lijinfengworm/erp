<?php

/**
 * TrdUserRecommendTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdUserRecommendTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdUserRecommendTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdUserRecommend');
    }
    public function getHasRecommend($uid,$type,$type_id)
    {
        return self::getInstance()->createQuery()
            ->where('user_id =?',$uid)
            ->addWhere('recommend_id = ?',$type_id)
            ->addWhere('is_delete = 0')
            ->fetchOne();
    }

    public function getListByUserId($userId)
    {
        return $this->createQuery()
            ->where('user_id = ?', $userId)
            ->andWhere('is_delete = 0')
            ->execute();
    }
}