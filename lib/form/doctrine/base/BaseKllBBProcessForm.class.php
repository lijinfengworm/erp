<?php

/**
 * KllBBProcess form base class.
 *
 * @method KllBBProcess getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllBBProcessForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'order_number' => new sfWidgetFormInputText(),
      'content'      => new sfWidgetFormInputText(),
      'sequence'     => new sfWidgetFormInputText(),
      'status'       => new sfWidgetFormInputText(),
      'update_time'  => new sfWidgetFormInputText(),
      'create_time'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'content'      => new sfValidatorPass(array('required' => false)),
      'sequence'     => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
      'update_time'  => new sfValidatorInteger(array('required' => false)),
      'create_time'  => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_process[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBProcess';
  }

}
