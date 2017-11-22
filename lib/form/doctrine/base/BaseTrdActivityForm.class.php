<?php

/**
 * TrdActivity form base class.
 *
 * @method TrdActivity getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdActivityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'root_type'      => new sfWidgetFormInputText(),
      'title'          => new sfWidgetFormInputText(),
      'list_id'        => new sfWidgetFormInputText(),
      'exchange_type'  => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'total'          => new sfWidgetFormInputText(),
      'limits'         => new sfWidgetFormInputText(),
      'recevied'       => new sfWidgetFormInputText(),
      'integral'       => new sfWidgetFormInputText(),
      'gold'           => new sfWidgetFormInputText(),
      'start_date'     => new sfWidgetFormInputText(),
      'expiry_date'    => new sfWidgetFormInputText(),
      'img_path'       => new sfWidgetFormInputText(),
      'content'        => new sfWidgetFormTextarea(),
      'grant_uid'      => new sfWidgetFormInputText(),
      'grant_username' => new sfWidgetFormInputText(),
      'mart'           => new sfWidgetFormInputText(),
      'receive_url'    => new sfWidgetFormInputText(),
      'is_delete'      => new sfWidgetFormInputCheckbox(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'root_type'      => new sfValidatorInteger(array('required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 200)),
      'list_id'        => new sfValidatorInteger(array('required' => false)),
      'exchange_type'  => new sfValidatorString(array('max_length' => 1, 'required' => false)),
      'type'           => new sfValidatorInteger(array('required' => false)),
      'total'          => new sfValidatorInteger(array('required' => false)),
      'limits'         => new sfValidatorInteger(array('required' => false)),
      'recevied'       => new sfValidatorInteger(array('required' => false)),
      'integral'       => new sfValidatorInteger(array('required' => false)),
      'gold'           => new sfValidatorInteger(array('required' => false)),
      'start_date'     => new sfValidatorPass(array('required' => false)),
      'expiry_date'    => new sfValidatorPass(array('required' => false)),
      'img_path'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'content'        => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'grant_uid'      => new sfValidatorInteger(array('required' => false)),
      'grant_username' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mart'           => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'receive_url'    => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'is_delete'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdActivity';
  }

}
