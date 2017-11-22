<?php

/**
 * TrdAccount form base class.
 *
 * @method TrdAccount getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAccountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'hupu_username'   => new sfWidgetFormInputText(),
      'integral'        => new sfWidgetFormInputText(),
      'gold'            => new sfWidgetFormInputText(),
      'shaiwu_integral' => new sfWidgetFormInputText(),
      'shaiwu_gold'     => new sfWidgetFormInputText(),
      'integral_total'  => new sfWidgetFormInputText(),
      'gold_total'      => new sfWidgetFormInputText(),
      'grant_uid'       => new sfWidgetFormInputText(),
      'grant_username'  => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'        => new sfValidatorInteger(array('required' => false)),
      'hupu_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'integral'        => new sfValidatorInteger(array('required' => false)),
      'gold'            => new sfValidatorInteger(array('required' => false)),
      'shaiwu_integral' => new sfValidatorInteger(array('required' => false)),
      'shaiwu_gold'     => new sfValidatorInteger(array('required' => false)),
      'integral_total'  => new sfValidatorInteger(array('required' => false)),
      'gold_total'      => new sfValidatorInteger(array('required' => false)),
      'grant_uid'       => new sfValidatorInteger(array('required' => false)),
      'grant_username'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'status'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_account[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAccount';
  }

}
