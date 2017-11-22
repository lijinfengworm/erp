<?php

/**
 * TrdSpecialVanclAwardNote form base class.
 *
 * @method TrdSpecialVanclAwardNote getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclAwardNoteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'match_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'add_empty' => true)),
      'type'       => new sfWidgetFormInputText(),
      'uid'        => new sfWidgetFormInputText(),
      'username'   => new sfWidgetFormInputText(),
      'award_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclAward'), 'add_empty' => true)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'deleted_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'match_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclMatch'), 'required' => false)),
      'type'       => new sfValidatorInteger(),
      'uid'        => new sfValidatorInteger(),
      'username'   => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'award_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialVanclAward'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'deleted_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_award_note[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclAwardNote';
  }

}
