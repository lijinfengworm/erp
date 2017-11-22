<?php

/**
 * TrdAccountHistory form base class.
 *
 * @method TrdAccountHistory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAccountHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'hupu_uid'        => new sfWidgetFormInputText(),
      'hupu_username'   => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'category'        => new sfWidgetFormInputText(),
      'source'          => new sfWidgetFormInputText(),
      'explanation'     => new sfWidgetFormInputText(),
      'actionid'        => new sfWidgetFormInputText(),
      'integral'        => new sfWidgetFormInputText(),
      'gold'            => new sfWidgetFormInputText(),
      'before_integral' => new sfWidgetFormInputText(),
      'before_gold'     => new sfWidgetFormInputText(),
      'after_integral'  => new sfWidgetFormInputText(),
      'after_gold'      => new sfWidgetFormInputText(),
      'grant_uid'       => new sfWidgetFormInputText(),
      'grant_username'  => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'        => new sfValidatorInteger(array('required' => false)),
      'hupu_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'type'            => new sfValidatorInteger(array('required' => false)),
      'category'        => new sfValidatorInteger(array('required' => false)),
      'source'          => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'explanation'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'actionid'        => new sfValidatorInteger(array('required' => false)),
      'integral'        => new sfValidatorInteger(array('required' => false)),
      'gold'            => new sfValidatorInteger(array('required' => false)),
      'before_integral' => new sfValidatorInteger(array('required' => false)),
      'before_gold'     => new sfValidatorInteger(array('required' => false)),
      'after_integral'  => new sfValidatorInteger(array('required' => false)),
      'after_gold'      => new sfValidatorInteger(array('required' => false)),
      'grant_uid'       => new sfValidatorInteger(array('required' => false)),
      'grant_username'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_account_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAccountHistory';
  }

}
