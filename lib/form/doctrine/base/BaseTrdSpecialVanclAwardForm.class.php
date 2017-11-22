<?php

/**
 * TrdSpecialVanclAward form base class.
 *
 * @method TrdSpecialVanclAward getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclAwardForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'probability'     => new sfWidgetFormInputText(),
      'is_limited'      => new sfWidgetFormInputText(),
      'num'             => new sfWidgetFormInputText(),
      'message_title'   => new sfWidgetFormInputText(),
      'message_content' => new sfWidgetFormInputText(),
      'send_uid'        => new sfWidgetFormInputText(),
      'send_username'   => new sfWidgetFormInputText(),
      'kaluli'          => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'deleted_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'probability'     => new sfValidatorInteger(array('required' => false)),
      'is_limited'      => new sfValidatorInteger(array('required' => false)),
      'num'             => new sfValidatorInteger(array('required' => false)),
      'message_title'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'message_content' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'send_uid'        => new sfValidatorInteger(array('required' => false)),
      'send_username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'kaluli'          => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'deleted_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_award[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclAward';
  }

}
