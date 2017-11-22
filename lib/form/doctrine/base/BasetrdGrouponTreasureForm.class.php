<?php

/**
 * trdGrouponTreasure form base class.
 *
 * @method trdGrouponTreasure getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasetrdGrouponTreasureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'hupu_uid'       => new sfWidgetFormInputText(),
      'hupu_username'  => new sfWidgetFormInputText(),
      'title'          => new sfWidgetFormInputText(),
      'intro'          => new sfWidgetFormTextarea(),
      'memo'           => new sfWidgetFormTextarea(),
      'price'          => new sfWidgetFormInputText(),
      'original_price' => new sfWidgetFormInputText(),
      'discount'       => new sfWidgetFormInputText(),
      'category_id'    => new sfWidgetFormInputText(),
      'brand_id'       => new sfWidgetFormInputText(),
      'url'            => new sfWidgetFormInputText(),
      'pic_attr'       => new sfWidgetFormTextarea(),
      'goods_num'      => new sfWidgetFormInputText(),
      'apply_for_time' => new sfWidgetFormInputText(),
      'start_time'     => new sfWidgetFormInputText(),
      'end_time'       => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'is_sold'        => new sfWidgetFormInputText(),
      'superiority'    => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'       => new sfValidatorInteger(array('required' => false)),
      'hupu_username'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'intro'          => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'memo'           => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'price'          => new sfValidatorNumber(array('required' => false)),
      'original_price' => new sfValidatorNumber(array('required' => false)),
      'discount'       => new sfValidatorNumber(array('required' => false)),
      'category_id'    => new sfValidatorInteger(array('required' => false)),
      'brand_id'       => new sfValidatorInteger(array('required' => false)),
      'url'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pic_attr'       => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'goods_num'      => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'apply_for_time' => new sfValidatorPass(array('required' => false)),
      'start_time'     => new sfValidatorPass(array('required' => false)),
      'end_time'       => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'is_sold'        => new sfValidatorInteger(array('required' => false)),
      'superiority'    => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_groupon_treasure[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdGrouponTreasure';
  }

}
