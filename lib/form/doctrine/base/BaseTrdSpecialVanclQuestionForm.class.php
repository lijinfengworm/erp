<?php

/**
 * TrdSpecialVanclQuestion form base class.
 *
 * @method TrdSpecialVanclQuestion getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclQuestionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'match_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'add_empty' => true)),
      'question'     => new sfWidgetFormInputText(),
      'answer'       => new sfWidgetFormTextarea(),
      'question_key' => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'match_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'required' => false)),
      'question'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'answer'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'question_key' => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_question[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclQuestion';
  }

}
