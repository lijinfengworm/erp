<?php

/**
 * TrdRegion form base class.
 *
 * @method TrdRegion getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdRegionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'region_id'   => new sfWidgetFormInputHidden(),
      'parent_id'   => new sfWidgetFormInputText(),
      'region_name' => new sfWidgetFormInputText(),
      'region_type' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'region_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('region_id')), 'empty_value' => $this->getObject()->get('region_id'), 'required' => false)),
      'parent_id'   => new sfValidatorInteger(array('required' => false)),
      'region_name' => new sfValidatorString(array('max_length' => 30)),
      'region_type' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_region[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdRegion';
  }

}
