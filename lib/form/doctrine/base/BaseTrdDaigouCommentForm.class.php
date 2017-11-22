<?php

/**
 * TrdDaigouComment form base class.
 *
 * @method TrdDaigouComment getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouCommentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'product_id' => new sfWidgetFormInputText(),
      'user_id'    => new sfWidgetFormInputText(),
      'user_name'  => new sfWidgetFormInputText(),
      'content'    => new sfWidgetFormInputText(),
      'imgs'       => new sfWidgetFormInputText(),
      'tags_attr'  => new sfWidgetFormTextarea(),
      'attr'       => new sfWidgetFormTextarea(),
      'status'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id' => new sfValidatorInteger(array('required' => false)),
      'user_id'    => new sfValidatorInteger(array('required' => false)),
      'user_name'  => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'content'    => new sfValidatorPass(array('required' => false)),
      'imgs'       => new sfValidatorPass(array('required' => false)),
      'tags_attr'  => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'attr'       => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouComment';
  }

}
