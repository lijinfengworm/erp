<?php

/**
 * KaluliOrderHistory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class KaluliOrderHistory extends BaseKaluliOrderHistory
{
    //状态
    public static $ORDER_CANCEL = 4;   //订单取消
    public static  $ORDER_PAYMENT = 1;


    //操作类型 0未付款 1客户已付款 2 虎扑已发货 3 订单完成 4订单取消 5 退款成功
    public static $HISTORY_TYPE = array(
        0=>'未付款',
        1=>'客户已付款',
        2=>'已发货',
        3=>'订单完成',
        4=>'订单取消',
        5=>'退款成功',
        6=>'退货处理中',
        7=>'待用户发货',
        8=>'待卡路里退货',
        9=>'已退货',
        10=>'订单关闭',
        11=>'拒绝退货',
        12=>'退款中',
    );

    public function getFormatType(){
        switch ($this->type) {
            case 0:
                return '已创建';
                break;
            case 1:
                return '已付款';
                break;
            case 2:
                return '已发货';
                break;
            case 3:
                return '订单完成 ';
                break;
            case 4:
                return '订单取消';
                break;
            case 5:
                return '退款成功';
                break;
            case 6:
                return '退货处理中';
                break;
            case 7:
                return '待用户发货';
                break;
            case 8:
                return '待卡路里退货';
                break;
            case 9:
                return '已退货';
                break;
            case 10:
                return '订单关闭';
                break;
            case 11:
                return '拒绝退货';
                break;
            case 12:
                return '退款中';
                break;
            default:
                break;
        }
    }

}