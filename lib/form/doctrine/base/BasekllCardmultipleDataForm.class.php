<?php

/**
 * kllCardmultipleData form base class.
 *
 * @method kllCardmultipleData getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasekllCardmultipleDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'm_card'     => new sfWidgetFormInputText(),
      'm_id'       => new sfWidgetFormInputText(),
      'card_data'  => new sfWidgetFormTextarea(),
      'uid'        => new sfWidgetFormInputText(),
      'u_time'     => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'm_card'     => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'm_id'       => new sfValidatorInteger(array('required' => false)),
      'card_data'  => new sfValidatorString(array('max_length' => 65535, 'required' => false)),
      'uid'        => new sfValidatorInteger(array('required' => false)),
      'u_time'     => new sfValidatorInteger(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_cardmultiple_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'kllCardmultipleData';
  }

}
