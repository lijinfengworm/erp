<?php

/**
 * KaluliOrderAttr form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliOrderAttrForm extends BaseKaluliOrderAttrForm
{
    /*  labels  */
    private $_labels = array(
        'order_number'=>'订单号',
        'attr'=>'商品sku',
        'refund_price'=>'商品退款额',
        'refund_express_fee'=>'运费退款额',
        'refund'=>'退款额',
        'ware_code'=>'仓库code',
    );


  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['order_number']);
      $this->widgetSchema->setLabels($this->_labels);


      $this->setWidget('ware_code', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('ware_code', new sfValidatorString(array('required' => false), array()));





  }
}
