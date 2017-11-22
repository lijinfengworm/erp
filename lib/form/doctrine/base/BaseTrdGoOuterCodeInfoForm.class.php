<?php

/**
 * TrdGoOuterCodeInfo form base class.
 *
 * @method TrdGoOuterCodeInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGoOuterCodeInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'click_id'         => new sfWidgetFormInputText(),
      'uid'              => new sfWidgetFormInputText(),
      'username'         => new sfWidgetFormInputText(),
      'cooick_id'        => new sfWidgetFormInputText(),
      'referer'          => new sfWidgetFormInputText(),
      'referer_host'     => new sfWidgetFormInputText(),
      'referer_id'       => new sfWidgetFormInputText(),
      'destination'      => new sfWidgetFormInputText(),
      'destination_host' => new sfWidgetFormInputText(),
      'click_time'       => new sfWidgetFormDateTime(),
      'item_name'        => new sfWidgetFormInputText(),
      'item_id'          => new sfWidgetFormInputText(),
      'item_price'       => new sfWidgetFormInputText(),
      'item_num'         => new sfWidgetFormInputText(),
      'item_type'        => new sfWidgetFormInputText(),
      'shop_nick'        => new sfWidgetFormInputText(),
      'trade_time'       => new sfWidgetFormDateTime(),
      'trade_commission' => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'deleted_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'click_id'         => new sfValidatorInteger(array('required' => false)),
      'uid'              => new sfValidatorInteger(array('required' => false)),
      'username'         => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'cooick_id'        => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'referer'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'referer_host'     => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'referer_id'       => new sfValidatorInteger(array('required' => false)),
      'destination'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'destination_host' => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'click_time'       => new sfValidatorDateTime(array('required' => false)),
      'item_name'        => new sfValidatorString(array('max_length' => 65, 'required' => false)),
      'item_id'          => new sfValidatorInteger(array('required' => false)),
      'item_price'       => new sfValidatorNumber(array('required' => false)),
      'item_num'         => new sfValidatorInteger(array('required' => false)),
      'item_type'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'shop_nick'        => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'trade_time'       => new sfValidatorDateTime(array('required' => false)),
      'trade_commission' => new sfValidatorNumber(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'deleted_at'       => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('uid'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('uid'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('username'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('cooick_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('referer_host'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('referer_host', 'referer_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('destination_host'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('click_time'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('item_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('shop_nick'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdGoOuterCodeInfo', 'column' => array('trade_time'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_go_outer_code_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoOuterCodeInfo';
  }

}
