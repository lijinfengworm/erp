<?php

/**
 * KllAttachment form base class.
 *
 * @method KllAttachment getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllAttachmentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'aid'      => new sfWidgetFormInputText(),
      'type'     => new sfWidgetFormInputText(),
      'original' => new sfWidgetFormInputText(),
      'medium'   => new sfWidgetFormInputText(),
      'small'    => new sfWidgetFormInputText(),
      'is_use'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'aid'      => new sfValidatorPass(array('required' => false)),
      'type'     => new sfValidatorPass(array('required' => false)),
      'original' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'medium'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'small'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_use'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_attachment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllAttachment';
  }

}
