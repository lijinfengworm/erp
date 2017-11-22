<?php

/**
 * TrdAdminChannel form base class.
 *
 * @method TrdAdminChannel getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminChannelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'identify'    => new sfWidgetFormInputText(),
      'channel'     => new sfWidgetFormInputText(),
      'manager'     => new sfWidgetFormInputText(),
      'create_time' => new sfWidgetFormInputText(),
      'update_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'identify'    => new sfValidatorPass(array('required' => false)),
      'channel'     => new sfValidatorPass(array('required' => false)),
      'manager'     => new sfValidatorInteger(array('required' => false)),
      'create_time' => new sfValidatorPass(array('required' => false)),
      'update_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_channel[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminChannel';
  }

}
