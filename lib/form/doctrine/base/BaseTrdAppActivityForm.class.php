<?php

/**
 * TrdAppActivity form base class.
 *
 * @method TrdAppActivity getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAppActivityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'title'          => new sfWidgetFormInputText(),
      'img_path'       => new sfWidgetFormInputText(),
      'price'          => new sfWidgetFormInputText(),
      'original_price' => new sfWidgetFormInputText(),
      'quantity'       => new sfWidgetFormInputText(),
      'unit'           => new sfWidgetFormInputText(),
      'limit'          => new sfWidgetFormInputText(),
      'received'       => new sfWidgetFormInputText(),
      'start_time'     => new sfWidgetFormInputText(),
      'end_time'       => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormTextarea(),
      'go_url'         => new sfWidgetFormTextarea(),
      'business'       => new sfWidgetFormInputText(),
      'business_url'   => new sfWidgetFormTextarea(),
      'is_delete'      => new sfWidgetFormInputCheckbox(),
      'grant_uid'      => new sfWidgetFormInputText(),
      'grant_username' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 200)),
      'img_path'       => new sfValidatorString(array('max_length' => 200)),
      'price'          => new sfValidatorNumber(),
      'original_price' => new sfValidatorNumber(),
      'quantity'       => new sfValidatorInteger(),
      'unit'           => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'limit'          => new sfValidatorInteger(array('required' => false)),
      'received'       => new sfValidatorInteger(array('required' => false)),
      'start_time'     => new sfValidatorPass(array('required' => false)),
      'end_time'       => new sfValidatorPass(array('required' => false)),
      'description'    => new sfValidatorString(array('max_length' => 3000, 'required' => false)),
      'go_url'         => new sfValidatorString(array('max_length' => 300)),
      'business'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'business_url'   => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'is_delete'      => new sfValidatorBoolean(array('required' => false)),
      'grant_uid'      => new sfValidatorInteger(array('required' => false)),
      'grant_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_app_activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppActivity';
  }

}
