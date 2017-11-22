<?php

/**
 * TrdGoodsCategory form base class.
 *
 * @method TrdGoodsCategory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'     => new sfWidgetFormInputHidden(),
      'pid'    => new sfWidgetFormInputText(),
      'name'   => new sfWidgetFormInputText(),
      'status' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'pid'    => new sfValidatorInteger(array('required' => false)),
      'name'   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'status' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsCategory';
  }

}
