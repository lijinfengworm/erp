<?php

/**
 * TrdDesire form base class.
 *
 * @method TrdDesire getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDesireForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'item_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItem'), 'add_empty' => true)),
      'item_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItemAll'), 'add_empty' => true)),
      'hupu_uid'    => new sfWidgetFormInputText(),
      'username'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItem'), 'required' => false)),
      'item_all_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItemAll'), 'required' => false)),
      'hupu_uid'    => new sfValidatorInteger(),
      'username'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'TrdDesire', 'column' => array('item_id', 'hupu_uid'))),
        new sfValidatorDoctrineUnique(array('model' => 'TrdDesire', 'column' => array('item_all_id', 'hupu_uid'))),
      ))
    );

    $this->widgetSchema->setNameFormat('trd_desire[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDesire';
  }

}
