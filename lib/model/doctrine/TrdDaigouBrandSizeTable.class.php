<?php

/**
 * TrdDaigouBrandSizeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdDaigouBrandSizeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdDaigouBrandSizeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdDaigouBrandSize');
    }

    //删除专题
    public static function del_size($id) {
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('id = ?',$id)
            ->delete();
        if(empty($data)) return false;
        return  $data->execute();
    }

    /**
     * 获取某个会员的卡密
     */
    public static function getAll($bind = array()) {
        $data = self::getInstance()->createQuery();
        //select
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


    public static function  setBrandSize($brand_id) {
        $_map['where']['brand_id'] = 'brand_id = '.$brand_id;
        $_map['where']['status'] = 'status  = '.TrdDaigouBrandSize::$SHOW_FLAG;
        $_map['select'] = 'id,title,content';
        $sizeData = TrdDaigouBrandSizeTable::getAll($_map);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $_key = TrdDaigouBrandSize::$CACHE_KEY.$brand_id;
        $redis->set($_key,serialize($sizeData),2592000);
        return $sizeData;
    }


}