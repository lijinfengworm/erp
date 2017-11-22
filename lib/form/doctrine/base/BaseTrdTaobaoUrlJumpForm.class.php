<?php

/**
 * TrdTaobaoUrlJump form base class.
 *
 * @method TrdTaobaoUrlJump getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdTaobaoUrlJumpForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'item_id'          => new sfWidgetFormInputText(),
      'jump_url'         => new sfWidgetFormTextarea(),
      'android_jump_url' => new sfWidgetFormTextarea(),
      'ios_jump_url'     => new sfWidgetFormTextarea(),
      'wp_jump_url'      => new sfWidgetFormTextarea(),
      'addtime'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'item_id'          => new sfValidatorInteger(),
      'jump_url'         => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'android_jump_url' => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'ios_jump_url'     => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'wp_jump_url'      => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'addtime'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_taobao_url_jump[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdTaobaoUrlJump';
  }

}
