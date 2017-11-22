<?php

/**
 * TrdAttrGroup form base class.
 *
 * @method TrdAttrGroup getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAttrGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'trd_attr_id'  => new sfWidgetFormInputHidden(),
      'trd_group_id' => new sfWidgetFormInputHidden(),
      'sort'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'trd_attr_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('trd_attr_id')), 'empty_value' => $this->getObject()->get('trd_attr_id'), 'required' => false)),
      'trd_group_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('trd_group_id')), 'empty_value' => $this->getObject()->get('trd_group_id'), 'required' => false)),
      'sort'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_attr_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAttrGroup';
  }

}
