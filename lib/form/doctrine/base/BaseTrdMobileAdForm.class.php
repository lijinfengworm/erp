<?php

/**
 * TrdMobileAd form base class.
 *
 * @method TrdMobileAd getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMobileAdForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'description'     => new sfWidgetFormInputText(),
      'banner_img_path' => new sfWidgetFormInputText(),
      'r_content'       => new sfWidgetFormInputText(),
      'r_content_color' => new sfWidgetFormInputText(),
      'r_url'           => new sfWidgetFormInputText(),
      'r_color'         => new sfWidgetFormInputText(),
      'c_content'       => new sfWidgetFormInputText(),
      'c_content_color' => new sfWidgetFormInputText(),
      'c_color'         => new sfWidgetFormInputText(),
      'is_cancel'       => new sfWidgetFormInputCheckbox(),
      'grant_uid'       => new sfWidgetFormInputText(),
      'grant_username'  => new sfWidgetFormInputText(),
      'start_time'      => new sfWidgetFormInputText(),
      'end_time'        => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'description'     => new sfValidatorString(array('max_length' => 200)),
      'banner_img_path' => new sfValidatorString(array('max_length' => 200)),
      'r_content'       => new sfValidatorString(array('max_length' => 20)),
      'r_content_color' => new sfValidatorString(array('max_length' => 10)),
      'r_url'           => new sfValidatorString(array('max_length' => 250)),
      'r_color'         => new sfValidatorString(array('max_length' => 10)),
      'c_content'       => new sfValidatorString(array('max_length' => 20)),
      'c_content_color' => new sfValidatorString(array('max_length' => 10)),
      'c_color'         => new sfValidatorString(array('max_length' => 10)),
      'is_cancel'       => new sfValidatorBoolean(array('required' => false)),
      'grant_uid'       => new sfValidatorInteger(),
      'grant_username'  => new sfValidatorString(array('max_length' => 50)),
      'start_time'      => new sfValidatorPass(),
      'end_time'        => new sfValidatorPass(),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_mobile_ad[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMobileAd';
  }

}
