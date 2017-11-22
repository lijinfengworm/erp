<?php

/**
 * wpOrder filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wpOrderFormFilter extends BasewpOrderFormFilter
{
  public function configure()
  {
      $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
                'choices' => array('' => '',
                                   '5' => '充值成功', 
                                   '4' =>'转账出错', 
                                   '1' => '充值后未转账', 
                                   '2' => '支付失败', 
                                   '6' =>'作废订单'),
                'renderer_class'=>'sfWidgetFormFilterSelect'
      ));
    
      $this->widgetSchema['source_type'] = new sfWidgetFormChoice(array(
                'choices' => array(''   => '', 
                                   '0'  => '用户', 
                                   '1'  => '后台',
                                   '11' => '返点', 
                                   '12' => '补单',
                                   '2'  => '骏网' ),
                'renderer_class'=>'sfWidgetFormFilterSelect'
      ));
    
      $this->widgetSchema['wpserver_id'] = new sfWidgetFormChoice(array(
                'choices' =>  wpServerTable::getServersWithGameName()
                
      ));

  }

 
}
