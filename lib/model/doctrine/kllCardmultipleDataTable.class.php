<?php

/**
 * kllCardmultipleDataTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class kllCardmultipleDataTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object kllCardmultipleDataTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('kllCardmultipleData');
    }

    //判断是否有重复的卡
    public static function isRepeat($m_card = '') {
        if($m_card) return false;
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('m_card = ?',$m_card)
            ->fetchOne();
        if(empty($data)) return false;
        return true;
    }


    /**
     * 获取一条记录所有生成的数量
     */
    public static function getCreateNum($m_id = '') {
        if(empty($m_id)) return 0;
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('m_id = ?',$m_id)
            ->count();
        return $data;
    }

    /**
     * 判断是否被使用了
     */
    public static function getExistUser($m_id = '',$user_id = '') {
        if(empty($m_id) || empty($user_id)) return 0;
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('m_id = ?',$m_id)
            ->andWhere('uid = ?',$user_id)
            ->count();
        return $data;
    }

    /**
     * 获取某个里面使用了多少张
     */
    public  static  function  getUnknownCard($m_id = '') {
        if(empty($m_id)) return 0;
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('m_id = ?',$m_id)
            ->andWhere('status = ?',KllCardmultipleData::$_STATUS_UNKNOWN)
            ->count();
        return $data;
    }


    /**
     * 获取一条未使用的
     * @param $status
     * @param string $type
     * @return bool
     */
    public static function getOneUnused($m_id = '') {
        if(empty($m_id)) return 0;
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('m_id = ?',$m_id)
            ->andWhere('status = ?',KllCardmultipleData::$_STATUS_UNKNOWN)
            ->limit(1)
            ->fetchOne();
        if(empty($data)) return false;
        return $data;
    }


    public static function getFormatStatus($status,$type = 'string'){
        $string = array(0=>'未使用',1=>'已使用');
        $html_one = array(0=>'<span class="c-red">未使用</span>',1=>'<span class="c-green">已使用</span>');
        $type = $$type;
        if(!empty($type[$status])) return $type[$status];
        return false;
    }

    /**
     * 获取全部的数据
     */
    public static function  getAll($bind = array()) {
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

    /**
     * 获取一条数据
     */
    public static function getOne($key = 'id',$val,$type = 'default') {
        $info = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere($key.' = ?',$val)
            ->fetchOne();
        if(empty($info)) return false;
        if(empty($type) || $type == 'default') return $info;
        if($type == 'array')  return  $info->toArray();
        return false;
    }

    /**
     * 删除数据
     */
    public static function del_cardmultiple($field,$id) {
        $data = self::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere($field.' = ?',$id)
            ->execute();
        return  $data->delete();
    }
}