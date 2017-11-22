<?php

/**
 * KllCommentTags form base class.
 *
 * @method KllCommentTags getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCommentTagsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'root_name'  => new sfWidgetFormInputText(),
      'child_name' => new sfWidgetFormInputText(),
      'attrs'      => new sfWidgetFormInputText(),
      'status'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'root_name'  => new sfValidatorString(array('max_length' => 128)),
      'child_name' => new sfValidatorString(array('max_length' => 128)),
      'attrs'      => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_comment_tags[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCommentTags';
  }

}
