<?php

/**
 * TrdLipinkaLargeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdLipinkaLargeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdLipinkaLargeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdLipinkaLarge');
    }


    //判断是否有重复的卡
    public static function isRepeat($card) {
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('card = ?',$card)
            ->fetchOne();
        if(empty($data)) return false;
        return true;
    }

    //删除大卡
    //删除指定记录
    public static function del_old($lipinka_id) {
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('lipinka_id = ?',$lipinka_id)
            ->execute();
        return  $data->delete();
    }


//获取指定礼品卡的记录
    public static function getLipinkaData($_id) {
        $info = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('lipinka_id = ?',$_id)
            ->fetchArray();
        if(empty($info)) return false;
        return $info;
    }



    /**
     * 如果有动态大卡 那么标记开始日期 和失效日期
     */
    public static function startCreate($_id,$_overdueTime = '') {
        if(empty($_id)) return false;
        $info =  self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('lipinka_id = ?',$_id)
            ->execute();
        foreach($info as $k=>$v) {
            if($v->getPostponeType() == TrdLipinkaRecord::$POSTPONE_DYNAMIC) {
                $record = TrdLipinkaRecordTable::getInstance()->find($v->getRecordId());
                $v->setOverdueTime($record->getOverdueTime());  //设置激活到期
                $v->save();
            }
        }
        return true;
    }


    //通过某个卡密获取一条记录
    public static function getByCardOne($card,$isToArray = true) {
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('card = ?',$card)
            ->fetchOne();
        if(empty($data)) return false;
        if($isToArray) return $data->toArray();
        return $data;
    }















}