<?php

/**
 * TrdGoodsNotice form base class.
 *
 * @method TrdGoodsNotice getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'goods_id'    => new sfWidgetFormInputText(),
      'supplier_id' => new sfWidgetFormInputText(),
      'pic'         => new sfWidgetFormInputText(),
      'tag_type'    => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'checked_at'  => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'goods_id'    => new sfValidatorInteger(array('required' => false)),
      'supplier_id' => new sfValidatorInteger(array('required' => false)),
      'pic'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tag_type'    => new sfValidatorInteger(array('required' => false)),
      'type'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'checked_at'  => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsNotice';
  }

}
