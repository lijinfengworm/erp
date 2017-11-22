<?php

/**
 * KllWarehousesTax form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllWarehousesTaxForm extends BaseKllWarehousesTaxForm
{
  private $_status =array(
        0 => '无税费',
        1 => '有税费'
  );

  public function configure()
  {
      //unset($this['warehouse_id']);
      //unset($this['tax_rule']);
      $this->setWidget('tax_start', new sfWidgetFormInput(array(), array('class'=>'w50')));
      $this->setValidator('tax_start',
        new sfValidatorInteger(array('required' => true, 'trim' => true),
            array('required' => '起征点必填')));

      $this->setWidget('tax_rate', new sfWidgetFormInput(array(), array('class'=>'w50')));
      $this->setValidator("tax_rate",new sfValidatorNumber(array('required' => true, 'trim' => true),
        array('required' => '税率必填！' )));


      $this->setWidget('tax_note',new sfWidgetFormTextarea(array(),array("class"=>"w460")));
      $this->setValidator("tax_note",new sfValidatorString(array('required' => true, 'trim' => true),
        array('required' => '说明必填！')));
      $this->setWidget('tax_rule',new tradeWidgetFormUeditor(array(),["row" => "3"]));
      $this->setValidator("tax_rule",new sfValidatorString(array('required' => true, 'trim'=> true),
        array('required' => '规则必填')));
      $this->setWidget("status", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_status,'default'=>0),array('class'=>' radio')));
      $this->setDefault('status',0);
      $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys($this->_status), 'required' => true), array('invalid' => '请选择状态', 'required'=>'请选择状态')));

      $this->setWidget('warehouse_id',new sfWidgetFormInputHidden());


      $this->widgetSchema->setHelps(array(
          'tax_start' => '<span class="c-999">仅限填写大与等于0的自然数,当起征点为0时,全额征税</span>',

      ));


      




      $this->widgetSchema->setLabels(array(
          'tax_start' => '税费起征点',
          'tax_rate' => '税率',
          'tax_note' => '税费说明',
          'tax_rule' => '税费规则',
          'status' => '是否设置税费'
      ));
  }
}
