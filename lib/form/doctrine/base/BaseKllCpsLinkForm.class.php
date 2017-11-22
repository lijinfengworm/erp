<?php

/**
 * KllCpsLink form base class.
 *
 * @method KllCpsLink getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCpsLinkForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'code'          => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormInputText(),
      'channel'       => new sfWidgetFormInputText(),
      'link'          => new sfWidgetFormInputText(),
      'uid'           => new sfWidgetFormInputText(),
      'cps_user_id'   => new sfWidgetFormInputText(),
      'cps_user_name' => new sfWidgetFormInputText(),
      'item_id'       => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code'          => new sfValidatorString(array('max_length' => 6, 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'channel'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'link'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'uid'           => new sfValidatorInteger(array('required' => false)),
      'cps_user_id'   => new sfValidatorInteger(array('required' => false)),
      'cps_user_name' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'item_id'       => new sfValidatorInteger(array('required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'KllCpsLink', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('kll_cps_link[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsLink';
  }

}
