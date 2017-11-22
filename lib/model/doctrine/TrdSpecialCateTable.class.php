<?php

/**
 * TrdSpecialCateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdSpecialCateTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdSpecialCateTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdSpecialCate');
    }

    public static function getUndelCate($isToArray = false)
    {
        $query = self::getInstance()->createQuery('m')
            ->where('m.deleted_at is NULL')
            ->execute();
        $items = array();
        if($isToArray) {
            if(!empty($query)) return $query->toArray();
            return false;
        } else {
            foreach ($query as $re) {
                $items[$re->getId()] = $re->getName();
            }
        }
        return $items;
    }


    /**
     * 获取所有展示在期刊里的栏目
     */
    public static function getShowJournalIds() {
        $query = self::getInstance()
            ->createQuery()
            ->select("id")
            ->andWhere('show_journal  = ?',1)
            ->fetchArray();
        if(empty($query)) return false;
        return FunBase::get_current_array($query,'id');
    }



    /**
     * 判断某个字段是否存在
     */
    public static function specialHasField($field,$value,$id) {
        $flag = self::getInstance()
            ->createQuery()
            ->select('id')
            ->andWhere($field.' = ?',$value);
        if(!empty($id)) {
            $flag->andWhere('id <> ?',$id);
        }
        return $flag->fetchOne();
    }




    /**
     * 删除栏目
     */
    public static function del_cate($id) {
        $data = self::getInstance()->find($id);
        if(empty($data)) return false;
        return $data->delete();
    }

    /*
    *获取主题名
    **/
    public static function getIdByName($name){
        $t = self::getInstance()->createQuery()->select('id')->whereIn('name', $name)->fetchArray();
        if($t){
            $return  = array();
            foreach($t as $t_val){
                $return[] = $t_val['id'];
            }
            return $return;
        }else{
            return false;
        }
    }

}