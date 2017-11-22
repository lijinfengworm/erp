<?php

/**
 * KllTalent form base class.
 *
 * @method KllTalent getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllTalentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'name'     => new sfWidgetFormInputText(),
      'job'      => new sfWidgetFormInputText(),
      'sex'      => new sfWidgetFormInputText(),
      'h_id'     => new sfWidgetFormInputText(),
      'att_id'   => new sfWidgetFormInputText(),
      'interest' => new sfWidgetFormInputText(),
      'add_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'job'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sex'      => new sfValidatorPass(array('required' => false)),
      'h_id'     => new sfValidatorPass(array('required' => false)),
      'att_id'   => new sfValidatorPass(array('required' => false)),
      'interest' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'add_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_talent[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTalent';
  }

}
