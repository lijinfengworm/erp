<?php

/**
 * TrdJDUrlJumpTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdJDUrlJumpTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdJDUrlJumpTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdJDUrlJump');
    }
    
    public static function getJdItemByItemUrl($item_url){
        if (empty($item_url)) return false;
        $query = self::getInstance()->createQuery('m')
                    ->where('encrypt_url = ?', substr(md5($item_url),0,8))
                    ->andWhere('url = ?', $item_url);
        return $query->fetchArray();     
    }
}