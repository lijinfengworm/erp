<?php

/**
 * TrdSpecialVanclMatch form base class.
 *
 * @method TrdSpecialVanclMatch getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclMatchForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'home_team'    => new sfWidgetFormInputText(),
      'away_team'    => new sfWidgetFormInputText(),
      'home_logo'    => new sfWidgetFormInputText(),
      'away_logo'    => new sfWidgetFormInputText(),
      'home_sustain' => new sfWidgetFormInputText(),
      'away_sustain' => new sfWidgetFormInputText(),
      'start_time'   => new sfWidgetFormDateTime(),
      'end_time'     => new sfWidgetFormDateTime(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'deleted_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'home_team'    => new sfValidatorString(array('max_length' => 30)),
      'away_team'    => new sfValidatorString(array('max_length' => 30)),
      'home_logo'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'away_logo'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'home_sustain' => new sfValidatorInteger(array('required' => false)),
      'away_sustain' => new sfValidatorInteger(array('required' => false)),
      'start_time'   => new sfValidatorDateTime(array('required' => false)),
      'end_time'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'deleted_at'   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdSpecialVanclMatch', 'column' => array('home_sustain'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdSpecialVanclMatch', 'column' => array('away_sustain'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_special_vancl_match[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclMatch';
  }

}
