<?php

/**
 * TrdDaigouShareHongbao form base class.
 *
 * @method TrdDaigouShareHongbao getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouShareHongbaoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'order_no'       => new sfWidgetFormInputText(),
      'hupu_uid'       => new sfWidgetFormInputText(),
      'hupu_username'  => new sfWidgetFormInputText(),
      'lipinka_amount' => new sfWidgetFormInputText(),
      'weixin_info'    => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_no'       => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'       => new sfValidatorInteger(),
      'hupu_username'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'lipinka_amount' => new sfValidatorInteger(array('required' => false)),
      'weixin_info'    => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_share_hongbao[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouShareHongbao';
  }

}
