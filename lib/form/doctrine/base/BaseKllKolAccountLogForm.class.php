<?php

/**
 * KllKolAccountLog form base class.
 *
 * @method KllKolAccountLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolAccountLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'kol_id'       => new sfWidgetFormInputText(),
      'log_channel'  => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'price'        => new sfWidgetFormInputText(),
      'ct_time'      => new sfWidgetFormDateTime(),
      'order_number' => new sfWidgetFormInputText(),
      'audit'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'kol_id'       => new sfValidatorInteger(array('required' => false)),
      'log_channel'  => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorPass(array('required' => false)),
      'price'        => new sfValidatorNumber(array('required' => false)),
      'ct_time'      => new sfValidatorDateTime(array('required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'audit'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_account_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolAccountLog';
  }

}
