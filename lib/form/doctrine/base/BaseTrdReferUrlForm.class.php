<?php

/**
 * TrdReferUrl form base class.
 *
 * @method TrdReferUrl getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdReferUrlForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'url'         => new sfWidgetFormInputText(),
      'encrypt_url' => new sfWidgetFormInputText(),
      'tp'          => new sfWidgetFormInputText(),
      'addtime'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'url'         => new sfValidatorString(array('max_length' => 200)),
      'encrypt_url' => new sfValidatorString(array('max_length' => 8)),
      'tp'          => new sfValidatorInteger(array('required' => false)),
      'addtime'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_refer_url[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdReferUrl';
  }

}
