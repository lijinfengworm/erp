<?php

/**
 * KllXbuy form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllXbuyForm extends BaseKllXbuyForm
{
  public function configure()
  {
    $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w460')));
    $this->setValidator('title',
        new sfValidatorString(array('required' => true, 'trim' => true),
            array('required' => '标题必填！')));

    $this->setWidget("description",new sfWidgetFormInput(array(),array("class"=>"w460")));
    $this->setValidator("description",new sfValidatorString(array("required"=>true,'trim' => true),array("required" => "简介必填")));
    $this->setWidget("detail_url",new sfWidgetFormInput(array(),array("class"=>"w460")));

    # 开始时间
    $this->setWidget('start_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
    $this->setValidator('start_time', new sfValidatorString(array('required' => true)));
    $this->setDefault('start_time', date('Y-m-d H:i:s'));
    # 结束时间
    $this->setWidget('end_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
    $this->setValidator('end_time', new sfValidatorString(array('required' => true)));
    $this->setDefault('end_time', date('Y-m-d H:i:s'));

    $this->widgetSchema->setHelps(array(
        'title' => '<span class="c-999">必填项,请输入一句话标题</span>',
        'description'  => '<span class="c-999">请输入一句话简介不超过15字</span>'

    ));

    $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
    );
  }

  public function myCallback($validator, $values) {

    if($values['end_time'] <= $values['start_time']) {
     throw new sfException('起始时间必须小于结束时间!');
    }
    return $values;
  }



}
