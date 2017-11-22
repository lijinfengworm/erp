<?php

/**
 * KllCardWare form base class.
 *
 * @method KllCardWare getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCardWareForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'code'          => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'stime'         => new sfWidgetFormInputText(),
      'etime'         => new sfWidgetFormInputText(),
      'phone'         => new sfWidgetFormInputText(),
      'is_delete'     => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'stime'         => new sfValidatorInteger(array('required' => false)),
      'etime'         => new sfValidatorInteger(array('required' => false)),
      'phone'         => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'is_delete'     => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_card_ware[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCardWare';
  }

}
