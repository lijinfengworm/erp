<?php

/**
 * KaluliLipinkaLarge form base class.
 *
 * @method KaluliLipinkaLarge getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliLipinkaLargeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'lipinka_id'    => new sfWidgetFormInputText(),
      'record_id'     => new sfWidgetFormInputText(),
      'card'          => new sfWidgetFormInputText(),
      'num'           => new sfWidgetFormInputText(),
      'no_receive'    => new sfWidgetFormInputText(),
      'postpone_type' => new sfWidgetFormInputText(),
      'postpone_day'  => new sfWidgetFormInputText(),
      'overdue_time'  => new sfWidgetFormInputText(),
      'stime'         => new sfWidgetFormInputText(),
      'etime'         => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lipinka_id'    => new sfValidatorInteger(array('required' => false)),
      'record_id'     => new sfValidatorInteger(array('required' => false)),
      'card'          => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'num'           => new sfValidatorInteger(array('required' => false)),
      'no_receive'    => new sfValidatorInteger(array('required' => false)),
      'postpone_type' => new sfValidatorInteger(array('required' => false)),
      'postpone_day'  => new sfValidatorInteger(array('required' => false)),
      'overdue_time'  => new sfValidatorInteger(array('required' => false)),
      'stime'         => new sfValidatorInteger(array('required' => false)),
      'etime'         => new sfValidatorInteger(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_lipinka_large[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliLipinkaLarge';
  }

}
