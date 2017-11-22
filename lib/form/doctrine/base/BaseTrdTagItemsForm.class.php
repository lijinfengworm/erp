<?php

/**
 * TrdTagItems form base class.
 *
 * @method TrdTagItems getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdTagItemsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'tag_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdTags'), 'add_empty' => false)),
      'item_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItemAll'), 'add_empty' => false)),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'tag_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdTags'))),
      'item_all_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TrdItemAll'))),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdTagItems', 'column' => array('tag_id', 'item_all_id')))
    );

    $this->widgetSchema->setNameFormat('trd_tag_items[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdTagItems';
  }

}
