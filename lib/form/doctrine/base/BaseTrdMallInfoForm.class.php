<?php

/**
 * TrdMallInfo form base class.
 *
 * @method TrdMallInfo getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdMallInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'domain'     => new sfWidgetFormInputText(),
      'name'       => new sfWidgetFormInputText(),
      'memo'       => new sfWidgetFormTextarea(),
      'link'       => new sfWidgetFormTextarea(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'domain'     => new sfValidatorString(array('max_length' => 20)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'memo'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'link'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdMallInfo', 'column' => array('domain')))
    );

    $this->widgetSchema->setNameFormat('trd_mall_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMallInfo';
  }

}
