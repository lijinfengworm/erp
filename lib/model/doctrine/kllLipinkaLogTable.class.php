<?php

/**
 * kllLipinkaLogTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class kllLipinkaLogTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object kllLipinkaLogTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('kllLipinkaLog');
    }


    public static function addLog($action_id,$table_id,$mssage,$hupu_uid = '',$username = '') {
        if(empty($action_id) || empty($table_id)) return false;
        $log = new KllLipinkaLog();
        $log->setActionId($action_id);
        $log->setTableId($table_id);
        $log->setMessage($mssage);
        if(empty($hupu_uid) && method_exists(sfContext::getInstance()->getUser(),"getTrdUserHuPuId")) $hupu_uid =   sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        if(empty($username) && method_exists(sfContext::getInstance()->getUser(),"getTrdUsername")) $username =   sfContext::getInstance()->getUser()->getTrdUsername();
        $log->setHupuUid(empty($hupu_uid) ? 0 : $hupu_uid);
        $log->setHupuUsername(empty($username) ? "系统" : $username);
        $log->save();
        return true;
    }



    public static function  getLipinkaLog($action_id,$table_id) {
        if(empty($action_id) || empty($table_id)) return false;
        $info = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('action_id = ?',$action_id)
            ->andWhere('table_id = ?',$table_id)
            ->orderBy('id DESC')
            ->fetchArray();
        if(empty($info)) return false;
        return $info;


    }






}