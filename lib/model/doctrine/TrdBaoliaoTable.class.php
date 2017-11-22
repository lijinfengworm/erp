<?php

/**
 * TrdBaoliaoTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdBaoliaoTable extends Doctrine_Table
{
    const HIDE = 1;
    const SHOW = 0;
    
    const STATUS_NORMAL = 0;
    const STATUS_BANNED = 1;
    const STATUS_BANNED_PERMANENT = 2;    
    
    /**
     * Returns an instance of this class.
     *
     * @return object TrdBaoliaoTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdBaoliao');
    }
    
    public static function myListQuery() {
        $query = self::getInstance()->createQuery('m')
                ->where('m.is_delete = 0')
                ->orderBy('m.created_at desc');
        return $query;
    }


    //按url查找
    public function get_by_url($url,$status = 0) {
        return self::getInstance()->createQuery()
            ->where('encrypt_url = ?', substr(md5($url),0,8))
            ->andWhere('url = ?', $url)
            ->andWhere('status = ?', $status)
            ->orderby("publish_date desc")
            ->fetchOne();
    }
    
    //按item_id查找
    public function get_by_item_id($item_id,$status = 0) {
        return self::getInstance()->createQuery()
            ->where('item_id = ?', $item_id)
            ->andWhere('status = ?', $status)
            ->orderby("publish_date desc")
            ->fetchOne();
    }
    
    public function getBaoliaoInfoById($baoliao_id)
    {
        return self::getInstance()->createQuery()->where('id =?',$baoliao_id)->fetchOne();
    }

    public static function getBaoliaoByUid($uid,$page=1,$pagesize=10)
    {
        $offset = ($page-1)*$pagesize;
        return self::getInstance()->createQuery()
            ->where('hupu_uid = ?', $uid)
            ->orderby("created_at desc")
            ->limit($pagesize)
            ->offset($offset)
            ->execute();
    }

    public static function getBaoliaoCountByUid($uid)
    {
        return self::getInstance()->createQuery()
            ->select('count(*) AS total')
            ->where('hupu_uid = ?', $uid)
            ->orderby("created_at desc")
            ->fetchOne();
    }


}