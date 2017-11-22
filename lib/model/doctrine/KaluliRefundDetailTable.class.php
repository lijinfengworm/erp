<?php

/**
 * KaluliRefundDetailTable
 * 卡路里订单退款记录操作表
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class KaluliRefundDetailTable extends Doctrine_Table
{


    //退款状态
    public static $STATIC_TYPE = array(
        0=>'待退款',
        1=>'退款完成',
        2=>'微信退款中',
    );


    //退款类型 0订单退款 1表示折扣退款
    public static $REFUND_TYPE = array(
        0=>'订单退款',
        1=>'折扣退款'
    );

    //待退款状态
    public static $WAIT_REFUND = 0;


    /**
     * Returns an instance of this class.
     *
     * @return object KaluliRefundDetailTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('KaluliRefundDetail');
    }


    //0待付款 1已支付 2待退款 3退款中 4退款完成 5退款失败
    public static function showStatus($show_flag,$type = 'string') {
        $string = & self::$STATIC_TYPE;
        $html_one = array(
            0=>'<span class="c-red">待退款</span>',
            1=>'<span class="c-green">退款完成</span>',
            2=>'<span class="c-green">微信退款中</span>',
        );
        $type = $$type;
        if(!empty($type[$show_flag])) return $type[$show_flag];
        return false;
    }

    //0待付款 1已支付 2待退款 3退款中 4退款完成 5退款失败
    public static function showType($show_flag,$type = 'string') {
        $string = & self::$REFUND_TYPE;
        $html_one = array(
            0=>'<span class="c-red">订单退款</span>',
            1=>'<span class="c-blue">折扣退款</span>',
        );
        $type = $$type;
        if(!empty($type[$show_flag])) return $type[$show_flag];
        return false;
    }

    /**
     * 通过自定义条件获取全部的数据
     * Abuout 梁天
     * ->from('TrdSpecial t')
     *   ->select('name,c.name ffff')->leftJoin('t.TrdSpecialCate c on t.cateid = c.id')
     */
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