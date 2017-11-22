<?php

/**
 * TrdProduct form base class.
 *
 * @method TrdProduct getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'trd_brand_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'add_empty' => true)),
      'item_id'       => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'url'           => new sfWidgetFormTextarea(),
      'price'         => new sfWidgetFormInputText(),
      'img_url'       => new sfWidgetFormTextarea(),
      'item_no'       => new sfWidgetFormInputText(),
      'size_ids'      => new sfWidgetFormInputText(),
      'category_ids'  => new sfWidgetFormInputText(),
      'style_ids'     => new sfWidgetFormInputText(),
      'color_ids'     => new sfWidgetFormInputText(),
      'sold_count'    => new sfWidgetFormInputText(),
      'is_soldout'    => new sfWidgetFormInputText(),
      'is_hide'       => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'trd_brand_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Brand'), 'required' => false)),
      'item_id'       => new sfValidatorInteger(array('required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 100)),
      'url'           => new sfValidatorString(array('max_length' => 2000)),
      'price'         => new sfValidatorNumber(array('required' => false)),
      'img_url'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'item_no'       => new sfValidatorString(array('max_length' => 60, 'required' => false)),
      'size_ids'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'category_ids'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'style_ids'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'color_ids'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'sold_count'    => new sfValidatorInteger(array('required' => false)),
      'is_soldout'    => new sfValidatorPass(array('required' => false)),
      'is_hide'       => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdProduct';
  }

}
