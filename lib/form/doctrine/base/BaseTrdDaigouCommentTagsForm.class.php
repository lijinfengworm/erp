<?php

/**
 * TrdDaigouCommentTags form base class.
 *
 * @method TrdDaigouCommentTags getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouCommentTagsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'daigou_comment_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DaigouComment'), 'add_empty' => false)),
      'tag_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tag'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'daigou_comment_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DaigouComment'))),
      'tag_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tag'))),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_comment_tags[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouCommentTags';
  }

}
