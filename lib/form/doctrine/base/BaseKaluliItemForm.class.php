<?php

/**
 * KaluliItem form base class.
 *
 * @method KaluliItem getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'title'          => new sfWidgetFormInputText(),
      'pic'            => new sfWidgetFormInputText(),
      'brand_id'       => new sfWidgetFormInputText(),
      'sell_point'     => new sfWidgetFormTextarea(),
      'intro'          => new sfWidgetFormTextarea(),
      'price'          => new sfWidgetFormInputText(),
      'discount_price' => new sfWidgetFormInputText(),
      'hits'           => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputText(),
      'status_es'      => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'pic'            => new sfValidatorString(array('max_length' => 235, 'required' => false)),
      'brand_id'       => new sfValidatorInteger(array('required' => false)),
      'sell_point'     => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'intro'          => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'price'          => new sfValidatorNumber(array('required' => false)),
      'discount_price' => new sfValidatorNumber(array('required' => false)),
      'hits'           => new sfValidatorInteger(array('required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'status_es'      => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItem';
  }

}
