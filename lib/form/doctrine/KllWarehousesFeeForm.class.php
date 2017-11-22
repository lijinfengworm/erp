<?php

/**
 * KllWarehousesFee form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllWarehousesFeeForm extends BaseKllWarehousesFeeForm
{
  public function configure()
  {
    unset($this['ct_time']);
    $this->setWidget('total_price', new sfWidgetFormInput(array(), array('class'=>'w50')));
    $this->setValidator('total_price',
        new sfValidatorNumber(array('required' => true, 'trim' => true),
            array("required"=>'费用必填!')));

    $this->setWidget('note',new sfWidgetFormTextarea(array(),array("class"=>"w460")));
    $this->setValidator("note",new sfValidatorString(array('required' => true, 'trim' => true),
        array('required' => '说明必填！')));

    $this->setWidget("warehouse_id",new sfWidgetFormInputHidden());
  
    $this->widgetSchema->setHelps(array(
        'total_price' => '<span class="c-999">必填项,仅限自然数,如果没有其他费用则填写"0"</span>',
        'note'  => '<span class="c-999">说明: 请填写其他费用的详细构成说明,前台不显示</span>'

    ));

    $this->widgetSchema->setLabels(array(
        'total_price' => '费用合计',
        'note' => '费用说明'
    ));

  }
}
