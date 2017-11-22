<?php

/**
 * TrdDaigouMenuTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdDaigouMenuTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdDaigouMenuTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdDaigouMenu');
    }


    /**
     *
     * 根据id获取菜单名
     */
    public function getMenuNameById($id){
        if (empty($id)) return false;
        return self::getInstance()->createQuery('a')
            ->select('*')
            ->where('a.id = ?', $id)
            ->fetchOne();
    }

    /**
     *
     * 获取子菜单
     * $type字段功能取消，字段还保留着
     */
    public function getchildrenMenu($type,$root_id){
        $query = self::getInstance()->createQuery('a')
            ->select('*')
            ->whereNotIn('a.level', array(0))
            ->orderby("a.id asc");
        if ($root_id) $query->andWhere('a.root_id = ?', $root_id);
        if ($type) $query->andWhere('a.type = ?', $type);
        return $query ->execute();
    }

    /**
     *
     * 根据id获取菜单名
     */
    public function getRootMenu($id ,$type){
        $query =  self::getInstance()->createQuery('a')
            ->select('*')
            ->where('a.level = ?', 0)
            ->orderby("a.sort asc");
        if ($type) $query->andWhere('a.type = ?', $type);
        return $query ->execute();
    }

    /**
     *
     * 判断是否存在二级菜单
     * $type字段功能取消，字段还保留着
     */
    public function getchildrenMenuCount($type,$root_id){
        if (empty($root_id)) return false;
        $query =  self::getInstance()->createQuery('a')
            ->select('*')
            ->where('a.root_id = ?', $root_id)
            ->whereNotIn('a.level', array(0))
            ->orderby("a.id asc");
        if ($type) $query->andWhere('a.type = ?', $type);
        return $query ->count();
    }

    /**
     *
     * 获取所有的一级菜单或者二级菜单
     * @param int $level
     * @return array
     */
    public static function getAllMenuName($level = 0, $root_id = null){
        $link   =  Doctrine_Manager::getInstance()->getConnection('trade');
        $result = Doctrine_Query::create($link)
            ->setResultCacheLifeSpan(60*60*2)
            ->useResultCache()
            ->select('t.id, t.name')
            ->from('TrdDaigouMenu t')
            ->where('t.level = ?', $level);

        if($level == 1 && $root_id) $result = $result->andWhere('t.root_id = ?', $root_id);
        $result = $result->orderBy('sort asc')->fetchArray();

        $arr = array();
        foreach($result as $k=>$v){
            $arr[$v['id']] =  $v['name'];
        }
        return $arr;
    }
}