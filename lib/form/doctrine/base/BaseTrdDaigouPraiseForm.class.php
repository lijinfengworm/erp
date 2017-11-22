<?php

/**
 * TrdDaigouPraise form base class.
 *
 * @method TrdDaigouPraise getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouPraiseForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'product_id'    => new sfWidgetFormInputText(),
      'hupu_uid'      => new sfWidgetFormInputText(),
      'hupu_username' => new sfWidgetFormInputText(),
      'create_time'   => new sfWidgetFormInputText(),
      'is_delete'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id'    => new sfValidatorInteger(array('required' => false)),
      'hupu_uid'      => new sfValidatorInteger(array('required' => false)),
      'hupu_username' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'create_time'   => new sfValidatorPass(array('required' => false)),
      'is_delete'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_praise[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouPraise';
  }

}
