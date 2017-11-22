<?php

/**
 * KllPurchaserAuth form base class.
 *
 * @method KllPurchaserAuth getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllPurchaserAuthForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'uid'         => new sfWidgetFormInputText(),
      'purchaser'   => new sfWidgetFormInputText(),
      'card_number' => new sfWidgetFormInputText(),
      'current_use' => new sfWidgetFormInputText(),
      'create_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'         => new sfValidatorInteger(array('required' => false)),
      'purchaser'   => new sfValidatorPass(array('required' => false)),
      'card_number' => new sfValidatorPass(array('required' => false)),
      'current_use' => new sfValidatorPass(array('required' => false)),
      'create_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_purchaser_auth[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllPurchaserAuth';
  }

}
