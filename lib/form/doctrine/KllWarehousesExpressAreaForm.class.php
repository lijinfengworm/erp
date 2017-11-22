<?php

/**
 * KllWarehousesExpressArea form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllWarehousesExpressAreaForm extends BaseKllWarehousesExpressAreaForm
{
  public function configure()
  {
    $this->setWidget("ware_express_id", new sfWidgetFormInputHidden());
    $this->setWidget("provinces", new sfWidgetFormInputHidden());
    $this->setWidget('is_default', new sfWidgetFormSelectRadio(["choices" => ['0' => '非默认', '1' => '默认'] , "label" => '默认', 'default' => 0], ['class' => 'redio fL']));
    $this->setWidget("first_price",new sfWidgetFormInput(array('label' =>'首重单价'), array('class'=>'w180')));
    $this->setWidget("additional_price",new sfWidgetFormInput(array('label' =>'续重单价'), array('class'=>'w180')));
    $this->setWidget("ct_time",new sfWidgetFormInputHidden());
  }
}
