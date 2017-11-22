<?php

/**
 * KllKolAccount form base class.
 *
 * @method KllKolAccount getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolAccountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kol_id'     => new sfWidgetFormInputHidden(),
      'account'    => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'kol_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('kol_id')), 'empty_value' => $this->getObject()->get('kol_id'), 'required' => false)),
      'account'    => new sfValidatorNumber(array('required' => false)),
      'type'       => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_account[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolAccount';
  }

}
