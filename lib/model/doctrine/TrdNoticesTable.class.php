<?php

/**
 * TrdNoticesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdNoticesTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdNoticesTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdNotices');
    }


    public static function getCount($uid,$type)
    {
        $query = self::getInstance()->createQuery()
            ->where('uid =?',$uid)
            ->andWhere('type =?',$type)
        ;
        return $query->count();
    }

    /**
     * @param int $page 页数
     * @param int $limit 条数
     * @param int $uid 用户id
     * @param int $type 类型
     * @return array
     */
    public static function getList($page =1 ,$limit =20, $uid, $type=1)
    {
        if( empty($uid) || empty($type) ) return null;
        $offset = ($page - 1) * $limit;
        $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        $query = " select a.*,b.content,b.extra,b.comment_id,b.reply_id from (SELECT * from trd_notices where uid='{$uid}' and type='{$type}' ORDER BY id desc limit {$offset}, {$limit}) as a join trd_notices_attr as b on a.id=b.notice_id ORDER BY a.id desc ";
        $statement = $connection->execute($query);
        $statement->execute();
        $resultset = $statement->fetchAll(PDO::FETCH_ASSOC);
       
        return $resultset;
    }
}