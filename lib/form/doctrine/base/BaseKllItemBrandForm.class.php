<?php

/**
 * KllItemBrand form base class.
 *
 * @method KllItemBrand getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllItemBrandForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'weight'      => new sfWidgetFormInputText(),
      'logo'        => new sfWidgetFormInputText(),
      'banner'      => new sfWidgetFormInputText(),
      'place'       => new sfWidgetFormInputText(),
      'place_en'    => new sfWidgetFormInputText(),
      'place_flag'  => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'ct_time'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'weight'      => new sfValidatorInteger(array('required' => false)),
      'logo'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'banner'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'place'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'place_en'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'place_flag'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'ct_time'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_item_brand[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllItemBrand';
  }

}
