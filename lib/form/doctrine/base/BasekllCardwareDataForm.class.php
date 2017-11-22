<?php

/**
 * kllCardwareData form base class.
 *
 * @method kllCardwareData getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllCardwareDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'w_id'       => new sfWidgetFormInputText(),
      'amount'     => new sfWidgetFormInputText(),
      'attr'       => new sfWidgetFormTextarea(),
      'alert_num'  => new sfWidgetFormInputText(),
      'is_alert'   => new sfWidgetFormInputText(),
      'cache_key'  => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'w_id'       => new sfValidatorInteger(array('required' => false)),
      'amount'     => new sfValidatorInteger(array('required' => false)),
      'attr'       => new sfValidatorString(array('max_length' => 3000, 'required' => false)),
      'alert_num'  => new sfValidatorInteger(array('required' => false)),
      'is_alert'   => new sfValidatorInteger(array('required' => false)),
      'cache_key'  => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_cardware_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllCardwareData';
  }

}
