<?php

/**
 * TrdCouponsList
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdCouponsList extends BaseTrdCouponsList
{
    public function getCouponsMall(){
        if ($this->getMall() == 1){
            return '优购网';
        }
        if ($this->getMall() == 2){
            return '亚马逊中国';
        }
        if ($this->getMall() == 3){
            return '京东商城';
        }
        if ($this->getMall() == 9){
            return '其他';
        }
        return "未知";
    }
}
