<?php

/**
 * TrdShopInfo form base class.
 *
 * @method TrdShopInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdShopInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'name'             => new sfWidgetFormInputText(),
      'owner_name'       => new sfWidgetFormInputText(),
      'shop_category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdShopCategory'), 'add_empty' => false)),
      'shop_user_id'     => new sfWidgetFormInputText(),
      'shop_info'        => new sfWidgetFormTextarea(),
      'memo'             => new sfWidgetFormTextarea(),
      'logo'             => new sfWidgetFormInputText(),
      'link'             => new sfWidgetFormTextarea(),
      'business'         => new sfWidgetFormInputText(),
      'location'         => new sfWidgetFormInputText(),
      'level'            => new sfWidgetFormInputText(),
      'good'             => new sfWidgetFormInputText(),
      'hupu_uid'         => new sfWidgetFormInputText(),
      'discount'         => new sfWidgetFormTextarea(),
      'charge'           => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'position'         => new sfWidgetFormInputText(),
      'verify_status'    => new sfWidgetFormInputText(),
      'collect_count'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 255)),
      'owner_name'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'shop_category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdShopCategory'))),
      'shop_user_id'     => new sfValidatorInteger(array('required' => false)),
      'shop_info'        => new sfValidatorString(array('max_length' => 3000, 'required' => false)),
      'memo'             => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'logo'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'link'             => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'business'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'location'         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'level'            => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'good'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'hupu_uid'         => new sfValidatorInteger(array('required' => false)),
      'discount'         => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'charge'           => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'position'         => new sfValidatorInteger(array('required' => false)),
      'verify_status'    => new sfValidatorInteger(array('required' => false)),
      'collect_count'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdShopInfo', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('trd_shop_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShopInfo';
  }

}
