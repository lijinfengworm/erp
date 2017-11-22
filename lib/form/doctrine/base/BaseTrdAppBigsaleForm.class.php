<?php

/**
 * TrdAppBigsale form base class.
 *
 * @method TrdAppBigsale getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAppBigsaleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'title'            => new sfWidgetFormInputText(),
      'banner_img_path'  => new sfWidgetFormInputText(),
      'background_color' => new sfWidgetFormInputText(),
      'imgs'             => new sfWidgetFormInputText(),
      'price'            => new sfWidgetFormInputText(),
      'original_price'   => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormInputText(),
      'go_url'           => new sfWidgetFormTextarea(),
      'is_delete'        => new sfWidgetFormInputCheckbox(),
      'share_content'    => new sfWidgetFormTextarea(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 200)),
      'banner_img_path'  => new sfValidatorString(array('max_length' => 200)),
      'background_color' => new sfValidatorString(array('max_length' => 10)),
      'imgs'             => new sfValidatorPass(),
      'price'            => new sfValidatorNumber(),
      'original_price'   => new sfValidatorNumber(),
      'description'      => new sfValidatorPass(array('required' => false)),
      'go_url'           => new sfValidatorString(array('max_length' => 300)),
      'is_delete'        => new sfValidatorBoolean(array('required' => false)),
      'share_content'    => new sfValidatorString(array('max_length' => 300)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_app_bigsale[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAppBigsale';
  }

}
