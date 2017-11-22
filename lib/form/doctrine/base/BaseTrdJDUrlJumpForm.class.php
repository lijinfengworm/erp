<?php

/**
 * TrdJDUrlJump form base class.
 *
 * @method TrdJDUrlJump getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdJDUrlJumpForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'encrypt_url' => new sfWidgetFormInputText(),
      'url'         => new sfWidgetFormTextarea(),
      'jump_url'    => new sfWidgetFormTextarea(),
      'addtime'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'encrypt_url' => new sfValidatorString(array('max_length' => 8)),
      'url'         => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'jump_url'    => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'addtime'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_jd_url_jump[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdJDUrlJump';
  }

}
