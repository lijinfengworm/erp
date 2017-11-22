<?php

/**
 * TrdClientPraise form base class.
 *
 * @method TrdClientPraise getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdClientPraiseForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'client_id'    => new sfWidgetFormInputText(),
      'client_str'   => new sfWidgetFormInputText(),
      'client_token' => new sfWidgetFormInputText(),
      'wpclient_str' => new sfWidgetFormInputText(),
      'news_id'      => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'create_time'  => new sfWidgetFormInputText(),
      'is_delete'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'client_id'    => new sfValidatorInteger(array('required' => false)),
      'client_str'   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'client_token' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'wpclient_str' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'news_id'      => new sfValidatorInteger(array('required' => false)),
      'type'         => new sfValidatorInteger(array('required' => false)),
      'create_time'  => new sfValidatorPass(array('required' => false)),
      'is_delete'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_client_praise[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdClientPraise';
  }

}
