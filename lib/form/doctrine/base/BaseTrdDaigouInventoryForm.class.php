<?php

/**
 * TrdDaigouInventory form base class.
 *
 * @method TrdDaigouInventory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouInventoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormInputText(),
      'intro'         => new sfWidgetFormInputText(),
      'front_pic'     => new sfWidgetFormInputText(),
      'type_id'       => new sfWidgetFormInputText(),
      'like_count'    => new sfWidgetFormInputText(),
      'goods_num'     => new sfWidgetFormInputText(),
      'goods_info'    => new sfWidgetFormTextarea(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'intro'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'front_pic'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type_id'       => new sfValidatorInteger(array('required' => false)),
      'like_count'    => new sfValidatorInteger(array('required' => false)),
      'goods_num'     => new sfValidatorInteger(array('required' => false)),
      'goods_info'    => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_inventory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouInventory';
  }

}
