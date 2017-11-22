<?php

/**
 * TrdGoClickInfo form base class.
 *
 * @method TrdGoClickInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoClickInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'uid'         => new sfWidgetFormInputText(),
      'username'    => new sfWidgetFormInputText(),
      'cooick_id'   => new sfWidgetFormInputText(),
      'referer'     => new sfWidgetFormInputText(),
      'destination' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'deleted_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'         => new sfValidatorInteger(array('required' => false)),
      'username'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'cooick_id'   => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'referer'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'destination' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'deleted_at'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_go_click_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoClickInfo';
  }

}
