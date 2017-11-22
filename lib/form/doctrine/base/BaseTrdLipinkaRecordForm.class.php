<?php

/**
 * TrdLipinkaRecord form base class.
 *
 * @method TrdLipinkaRecord getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdLipinkaRecordForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'lipinka_id'    => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'is_large'      => new sfWidgetFormInputText(),
      'large_id'      => new sfWidgetFormInputText(),
      'postpone_type' => new sfWidgetFormInputText(),
      'postpone_day'  => new sfWidgetFormInputText(),
      'overdue_day'   => new sfWidgetFormInputText(),
      'overdue_time'  => new sfWidgetFormInputText(),
      'stime'         => new sfWidgetFormInputText(),
      'etime'         => new sfWidgetFormInputText(),
      'amount'        => new sfWidgetFormInputText(),
      'num'           => new sfWidgetFormInputText(),
      'accept_uids'   => new sfWidgetFormTextarea(),
      'is_success'    => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lipinka_id'    => new sfValidatorInteger(array('required' => false)),
      'type'          => new sfValidatorInteger(array('required' => false)),
      'is_large'      => new sfValidatorInteger(array('required' => false)),
      'large_id'      => new sfValidatorInteger(array('required' => false)),
      'postpone_type' => new sfValidatorInteger(array('required' => false)),
      'postpone_day'  => new sfValidatorInteger(array('required' => false)),
      'overdue_day'   => new sfValidatorInteger(array('required' => false)),
      'overdue_time'  => new sfValidatorInteger(array('required' => false)),
      'stime'         => new sfValidatorInteger(array('required' => false)),
      'etime'         => new sfValidatorInteger(array('required' => false)),
      'amount'        => new sfValidatorInteger(array('required' => false)),
      'num'           => new sfValidatorInteger(array('required' => false)),
      'accept_uids'   => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'is_success'    => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_lipinka_record[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdLipinkaRecord';
  }

}
