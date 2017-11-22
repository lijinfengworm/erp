<?php

/**
 * TrdOrderActivityDetail form base class.
 *
 * @method TrdOrderActivityDetail getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdOrderActivityDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'order_number' => new sfWidgetFormInputText(),
      'activity_id'  => new sfWidgetFormInputText(),
      'attr'         => new sfWidgetFormTextarea(),
      'type'         => new sfWidgetFormInputText(),
      'refund_type'  => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'activity_id'  => new sfValidatorInteger(array('required' => false)),
      'attr'         => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'type'         => new sfValidatorInteger(array('required' => false)),
      'refund_type'  => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_order_activity_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdOrderActivityDetail';
  }

}