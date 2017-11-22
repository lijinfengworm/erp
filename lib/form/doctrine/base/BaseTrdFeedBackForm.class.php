<?php

/**
 * TrdFeedBack form base class.
 *
 * @method TrdFeedBack getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdFeedBackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'content'       => new sfWidgetFormTextarea(),
      'email'         => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'type'          => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'content'       => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'email'         => new sfValidatorString(array('max_length' => 254, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_feed_back[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdFeedBack';
  }

}
