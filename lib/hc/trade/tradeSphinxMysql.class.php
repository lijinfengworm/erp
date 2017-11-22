<?php

/*
 * sphinx链接数据库操作
 */

class tradeSpinxMysql {

    public static $mysql_object = null; //mysql对象 
    
    /*
     * 进行一些初始化工作
     */

    public function __construct()
    {
        $this->getConn();
    }

    /*
     * 设置mysql对象
     */

    public function getConn()
    {
        if (!self::$mysql_object)
        {
            self::$mysql_object = sfContext::getInstance()->getDatabaseConnection('mysphinxMysql');
        }

        return self::$mysql_object;
    }

    /**
    *
    * 保存记录到sphinx中
    * @param int $id
    * @return boolean 
    */
    public function saveData($data,$table='find'){
      if (!is_array($data)) return false;
      $number_type = array('id','hot','price','time');

      $list = '';
      $values = '';
      foreach ($data as $k=>$v){
          $list .= $k.',';
          if (in_array($k,$number_type)){
              $values .= $v.',';
          } else {
              $v = addslashes($v);
              $values .= "'$v'".',';
          }
      }
      $sql = 'replace into '.$table.' ('.rtrim($list,',').') values('.rtrim($values,',').')';
      $result = mysql_query($sql,self::$mysql_object);
      return $result;
  }
  
  /**
    *
    * sphinx搜索
    * @param int $offset 取值位置
    * @param int $limit 要取得条数
    * @param string $where  条件 
    * @param string $sort 排序
    * @param int $max_matches 最大取值数
    * @return array 
    */
  public function search($offset = 0, $limit = 100, $where, $sort = 'time desc', $table='find', $param=array()){
      $sql = "select id from ".$table;
      $total_sql = "select count(*) from ".$table;
      if (empty($where)){
           $sql .= ' where id > 0';
           $total_sql .= ' where id > 0';
      } else {
           $sql .= " where match('".$where ."')";
           $total_sql .= " where match('".$where ."')";
      }
      /*   设定发布日期定时发布    */
      if($table == 'news'){
           $sql .= " and time <= ".time();
           $total_sql .= " and time <= ".time();
      }
      /*************************/
     if (isset($param['type']) && !empty($param['type'])){//优惠信息 类型判断
          $sql .=  " and type=".$param['type'];
          $total_sql .=  " and type=".$param['type'];
      } else if ($table == 'news') {//取显示在首页的
          $sql .=  " and display=1";
          $total_sql .=  " and display=1";
      }
      if (isset($param['mall']) && !empty($param['mall'])){//优惠信息 商城判断
          $sql .=  " and mall=".$param['mall'];
          $total_sql .=  " and mall=".$param['mall'];
      }
      if (isset($param['shopping']) && !empty($param['shopping'])){//优惠信息 是否需要代购
          $sql .=  " and info1=".$param['shopping'];
          $total_sql .=  " and info1=".$param['shopping'];
      }
      if (isset($param['root_type']) && !empty($param['root_type'])){//海淘类型
          $sql .=  " and info2 in (".$param['root_type'].")";
          $total_sql .=  " and info2 in (".$param['root_type'].")";
      }


      /*************发现好货是否展示运动鞋，为1表示展示******************/
      if($table == 'find'){
          if (isset($param['display']) && !empty($param['display'])){
              $sql .= " and display = 1";
              $total_sql .= " and display = 1";
          }
      }
      /*************发现好货是否展示运动鞋，为1表示展示 END******************/
      $sql .= " order by ".$sort ." limit ".$offset.",".$limit;
      $total_sql .= " order by ".$sort;

      $data = mysql_query($sql,self::$mysql_object);
      $total = mysql_query($total_sql,self::$mysql_object);
      $return_data = array();
      while($row = mysql_fetch_row($data)){
          $return_data[] = $row[0];
      }
      $return_total = mysql_fetch_row($total);
      return array('data'=>$return_data,'total'=>$return_total[0]);
  }
  
  /**
    *
    * sphinx搜索条数
    * @param string $where  条件 
    * @param string $sort 排序
    * @return int 
    */
  public function searchCount($where, $sort = 'time desc', $table='find', $param=array()){
      $sql = "select id from ".$table;
      $total_sql = "select count(*) from ".$table;
      if (empty($where)){
           $total_sql .= ' where id > 0';
      } else {
           $total_sql .= " where match('".$where ."')";
      }
     if (isset($param['type']) && !empty($param['type'])){//优惠信息 类型判断
          $total_sql .=  " and type=".$param['type'];
      } else if ($table == 'news') {//取显示在首页的
          $total_sql .=  " and display=1";
      }
      if (isset($param['mall']) && !empty($param['mall'])){//优惠信息 商城判断
          $total_sql .=  " and mall=".$param['mall'];
      }
      $total_sql .= " order by ".$sort;
      $total = mysql_query($total_sql,self::$mysql_object);
      $return_total = mysql_fetch_row($total);
      return array($return_total);
  }
  
  /**
    *
    * sphinx搜索(详情页面专用)
    * @param int $limit 要取得条数
    * @param string $where  条件 
    * @param string $sort 排序
    * @return array 
    */
  public function detailSearch($item_id, $limit = 10, $where,$param=array()){
      if (empty($where)) return array();
      $sql = "select id,WEIGHT() `weight` from find where id<>$item_id and match('".$where ."')";
      if (isset($param['time']) && !empty($param['time'])) $sql .= ' and time >'.$param['time'];
      if (isset($param['price_top']) && !empty($param['price_top']) && isset($param['price_bottom']) && !empty($param['price_bottom'])) $sql .= ' and price >'.$param['price_bottom'].' and price <'.$param['price_top'];
      $sql .=" ORDER BY weight() desc,time desc limit ".$limit." option field_weights=(info=1)";
      $data = mysql_query($sql,self::$mysql_object);
      $return_data = array();
      while($row = mysql_fetch_row($data)){
          $return_data[] = $row[0];
      }
      return $return_data;
  }
  
/**
*
 * 删除sphinx中的某条记录
* @param int $id
* @return boolean 
*/
  public function deleteOne($id,$table = 'find'){
      if (empty($id)) return false;
      $sql = "delete from ".$table." where id =".$id;
      if (mysql_query($sql,self::$mysql_object)) return true;
      return false;
  }

}
