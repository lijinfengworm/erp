<?php

/**
 * TrdBusinessman form base class.
 *
 * @method TrdBusinessman getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdBusinessmanForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'username'       => new sfWidgetFormInputText(),
      'hupu_uid'       => new sfWidgetFormInputText(),
      'hupu_username'  => new sfWidgetFormInputText(),
      'phone'          => new sfWidgetFormInputText(),
      'email'          => new sfWidgetFormInputText(),
      'qq'             => new sfWidgetFormInputText(),
      'shop_url'       => new sfWidgetFormInputText(),
      'shop_name'      => new sfWidgetFormInputText(),
      'wanwan'         => new sfWidgetFormInputText(),
      'alliance'       => new sfWidgetFormInputText(),
      'alliance_trdno' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'username'       => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'hupu_uid'       => new sfValidatorInteger(array('required' => false)),
      'hupu_username'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'phone'          => new sfValidatorInteger(array('required' => false)),
      'email'          => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'qq'             => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'shop_url'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'shop_name'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'wanwan'         => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'alliance'       => new sfValidatorInteger(array('required' => false)),
      'alliance_trdno' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_businessman[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBusinessman';
  }

}
