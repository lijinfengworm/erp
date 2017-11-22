<?php

/**
 * TrdGoodsStyle form base class.
 *
 * @method TrdGoodsStyle getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsStyleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'goods_id'   => new sfWidgetFormInputText(),
      'name'       => new sfWidgetFormInputText(),
      'pic'        => new sfWidgetFormInputText(),
      'value'      => new sfWidgetFormInputText(),
      'is_default' => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'hits'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'goods_id'   => new sfValidatorInteger(array('required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'pic'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'value'      => new sfValidatorPass(array('required' => false)),
      'is_default' => new sfValidatorInteger(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'hits'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_style[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsStyle';
  }

}
