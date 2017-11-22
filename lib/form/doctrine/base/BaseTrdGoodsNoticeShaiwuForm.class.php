<?php

/**
 * TrdGoodsNoticeShaiwu form base class.
 *
 * @method TrdGoodsNoticeShaiwu getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsNoticeShaiwuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'shaiwu_id' => new sfWidgetFormInputText(),
      'time'      => new sfWidgetFormInputText(),
      'attrs'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'shaiwu_id' => new sfValidatorInteger(array('required' => false)),
      'time'      => new sfValidatorInteger(array('required' => false)),
      'attrs'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_notice_shaiwu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsNoticeShaiwu';
  }

}
