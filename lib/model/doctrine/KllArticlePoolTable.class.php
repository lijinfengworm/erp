<?php

/**
 * KllArticlePoolTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KllArticlePoolTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object KllArticlePoolTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KllArticlePool');
    }
    public static function getAll($bind = []){

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