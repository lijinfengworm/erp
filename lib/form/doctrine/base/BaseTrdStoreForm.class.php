<?php

/**
 * TrdStore form base class.
 *
 * @method TrdStore getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdStoreForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'sort'       => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
      'is_index'   => new sfWidgetFormInputCheckbox(),
      'is_haitao'  => new sfWidgetFormInputCheckbox(),
      'is_display' => new sfWidgetFormInputCheckbox(),
      'is_delete'  => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 30)),
      'sort'       => new sfValidatorInteger(array('required' => false)),
      'type'       => new sfValidatorInteger(array('required' => false)),
      'is_index'   => new sfValidatorBoolean(array('required' => false)),
      'is_haitao'  => new sfValidatorBoolean(array('required' => false)),
      'is_display' => new sfValidatorBoolean(array('required' => false)),
      'is_delete'  => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_store[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdStore';
  }

}
