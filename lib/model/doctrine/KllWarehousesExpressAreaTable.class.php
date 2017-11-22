<?php

/**
 * KllWarehousesExpressAreaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllWarehousesExpressAreaTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllWarehousesExpressAreaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllWarehousesExpressArea');
    }
    public static function getDatas($bind = []){
        $data = self::getInstance()->createQuery();
        if (!empty($bind['select'])){
            $data->select($bind['select']);
        } else {
            $data->select("*");
        }
        //where 简单判断  如果复杂 建议新写函数
        if(!empty($bind['where']) && count($bind['where']) > 0) {
            foreach($bind['where'] as $k=>$v) {
                $data->addWhere($v);
            }
        }
        //limit
        if (!empty($bind['limit'])){
            $data->limit($bind['limit']);
        }
        //order
        if (!empty($bind['order'])){
            $data->orderBy($bind['order']);
        } else {
            $data->orderBy('id desc');
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