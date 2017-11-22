<?php

/**
 * TrdSpecialVanclAnswer form base class.
 *
 * @method TrdSpecialVanclAnswer getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclAnswerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'question_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclQuestion'), 'add_empty' => true)),
      'uid'         => new sfWidgetFormInputText(),
      'username'    => new sfWidgetFormInputText(),
      'answer_key'  => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'question_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclQuestion'), 'required' => false)),
      'uid'         => new sfValidatorInteger(),
      'username'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'answer_key'  => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclAnswer';
  }

}
