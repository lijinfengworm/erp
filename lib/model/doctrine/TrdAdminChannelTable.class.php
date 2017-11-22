<?php

/**
 * TrdAdminChannelTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdAdminChannelTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdAdminChannelTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdAdminChannel');
    }
    /**
     * 获取可用的用户组
     * $is_simple  是否自定义显示字段
     */
    public static function getNormalChannel($field = '',$status = 1) {
        $result = array();
        $field ?  $_field = $field : $_field = '*';
        $data = self::getInstance()
            ->createQuery()
            ->select($_field);
        if($status != 'all') {
            $data->andwhere("status = ?",1);
        }
        $data = $data->fetchArray();
        if(isset($data)) {
            foreach ($data as $k => $v) {
                $result[$v['id']] = $v['channel'];
            }
        }
        return $result;
    }
}