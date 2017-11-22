<?php

/**
 * KllCustomLogsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllCustomLogsTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllCustomLogsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllCustomLogs');
    }
    
    public static function  getAll($bind = array()) {
        $data = self::getInstance()->createQuery();
        //select
        if (!empty($bind['select'])){
            $data->select($bind['select']);
        } else {
            $data->select("*");
        }
        //leftJoin
        if (!empty($bind['leftJoin'])){
            $data->leftJoin($bind['leftJoin']);
        }
        //where 简单判断  如果复杂 建议新写函数
        if(!empty($bind['where']) && count($bind['where']) > 0) {
            foreach($bind['where'] as $k=>$v) {
                $data->addWhere($v);
            }
        }

        //whereIn 简单判断  如果复杂 建议新写函数
        if(!empty($bind['whereIn']) && count($bind['whereIn']) > 0) {
            foreach($bind['whereIn'] as $k=>$v) {
                $data->WhereIn($k,$v);
            }
        }

        //order
        if (!empty($bind['order'])){
            $data->orderBy($bind['order']);
        } else {
            $data->orderBy('id desc');
        }
        //limit
        if (!empty($bind['limit'])){
            $data->limit($bind['limit']);
        }

        if(!empty($bind['offset'])) {
            $data->offset($bind['offset']);
        }

        $data =  $data->fetchArray();
        if(!empty($bind['is_count'])) {
            $data = $data[0]['num'];
        }
        return $data;
    }
}