<?php

/**
 * TrdShoutao form base class.
 *
 * @method TrdShoutao getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdShoutaoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'tid'         => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'item_id'     => new sfWidgetFormInputText(),
      'item_url'    => new sfWidgetFormInputText(),
      'pic'         => new sfWidgetFormInputText(),
      'recommend'   => new sfWidgetFormInputText(),
      'tags'        => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'send_time'   => new sfWidgetFormInputText(),
      'content_img' => new sfWidgetFormInputText(),
      'admin_id'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'tid'         => new sfValidatorPass(),
      'title'       => new sfValidatorString(array('max_length' => 25)),
      'item_id'     => new sfValidatorPass(),
      'item_url'    => new sfValidatorString(array('max_length' => 255)),
      'pic'         => new sfValidatorString(array('max_length' => 255)),
      'recommend'   => new sfValidatorPass(),
      'tags'        => new sfValidatorString(array('max_length' => 100)),
      'type'        => new sfValidatorInteger(array('required' => false)),
      'send_time'   => new sfValidatorPass(array('required' => false)),
      'content_img' => new sfValidatorString(array('max_length' => 255)),
      'admin_id'    => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_shoutao[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShoutao';
  }

}
