<?php

/**
 * TrdGoods form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsForm extends BaseTrdGoodsForm
{
    public static $type = array(
        1=>'篮球',
        2=>'跑步',
        3=>'休闲',
        4=>'其他',
    );

    public static $fromType = array(
        1=>'优惠信息',
        3=>'海淘商品',
        4=>'团购申请',
        5=>'爆料来源',
        6=>'手动添加'
    );

    public static $status = array(
        0=>'未完善',
        1=>'已完善',
    );


  public function configure()
  {

  }
}
