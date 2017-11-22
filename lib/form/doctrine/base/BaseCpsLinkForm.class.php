<?php

/**
 * CpsLink form base class.
 *
 * @method CpsLink getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCpsLinkForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'code'       => new sfWidgetFormInputText(),
      'title'      => new sfWidgetFormInputText(),
      'link'       => new sfWidgetFormInputText(),
      'uid'        => new sfWidgetFormInputText(),
      'is_fav'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code'       => new sfValidatorString(array('max_length' => 6, 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'link'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'uid'        => new sfValidatorInteger(array('required' => false)),
      'is_fav'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'CpsLink', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('cps_link[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CpsLink';
  }

}
