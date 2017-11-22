<?php

/**
 * KllWarehousesExpress form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllWarehousesExpressForm extends BaseKllWarehousesExpressForm
{
  public function configure()
  {
    //$this->setWidget("express_id",new sfWidgetFormInput(array('label' =>'快递ID'), array('class'=>'w180')));
    
    
  	$this->setWidget('express_id', new sfWidgetFormInputHidden());
    $this->setWidget('status', new sfWidgetFormSelectRadio(["choices" => ['0' => '停用', '1' => '启用'] , "label" => '状态', 'default' => 1], ['class' => 'redio fL']));

    $this->setWidget("radio",new sfWidgetFormInput(array('label' =>'首重系数'), array('class'=>'w180')));
    $this->setWidget('is_default', new sfWidgetFormSelectRadio(["choices" => ['0' => '非默认', '1' => '默认'] , "label" => '默认', 'default' => 0], ['class' => 'redio fL']));
    //$this->setWidget("express_id", new sfWidgetFormSelect(array("label" => '快递', 'choices'=>)));
    $this->setWidget("warehouse_id", new sfWidgetFormInputHidden());
    $this->setWidget("ct_time", new sfWidgetFormInputHidden());

  }
  
}
