<?php

/**
 * KllErpOrderExplain form base class.
 *
 * @method KllErpOrderExplain getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllErpOrderExplainForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'order_number' => new sfWidgetFormInputText(),
      'remark'       => new sfWidgetFormInputText(),
      'pic'          => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'user'         => new sfWidgetFormInputText(),
      'create_time'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'remark'       => new sfValidatorPass(array('required' => false)),
      'pic'          => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorPass(array('required' => false)),
      'user'         => new sfValidatorInteger(array('required' => false)),
      'create_time'  => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_order_explain[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpOrderExplain';
  }

}
