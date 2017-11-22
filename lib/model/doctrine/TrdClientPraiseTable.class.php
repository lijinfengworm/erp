<?php

/**
 * TrdClientPraiseTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdClientPraiseTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdClientPraiseTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdClientPraise');
    }
    
    /*
     * 点赞
     */
    public static function getPraiseInfoByNewsId($clientcode,$newsId,$type = 1){
        if (!$clientcode || !$newsId) return false;
        $query =  self::getInstance()->createQuery('t')
                ->where('t.client_str = ?',$clientcode)
                ->andwhere('t.news_id = ?',$newsId)
                ->andwhere('t.type = ?',$type);
        return  $query->fetchOne();
    }
}