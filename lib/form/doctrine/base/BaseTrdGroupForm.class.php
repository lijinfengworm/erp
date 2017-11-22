<?php

/**
 * TrdGroup form base class.
 *
 * @method TrdGroup getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'sort'       => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
      'flag'       => new sfWidgetFormInputText(),
      'menu_id'    => new sfWidgetFormInputText(),
      'attr'       => new sfWidgetFormTextarea(),
      'usage'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 30)),
      'sort'       => new sfValidatorInteger(array('required' => false)),
      'type'       => new sfValidatorInteger(array('required' => false)),
      'flag'       => new sfValidatorInteger(array('required' => false)),
      'menu_id'    => new sfValidatorInteger(array('required' => false)),
      'attr'       => new sfValidatorString(array('max_length' => 5000, 'required' => false)),
      'usage'      => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGroup';
  }

}
