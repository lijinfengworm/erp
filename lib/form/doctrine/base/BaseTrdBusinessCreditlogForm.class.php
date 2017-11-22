<?php

/**
 * TrdBusinessCreditlog form base class.
 *
 * @method TrdBusinessCreditlog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdBusinessCreditlogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'uid'      => new sfWidgetFormInputText(),
      'admin_id' => new sfWidgetFormInputText(),
      'type'     => new sfWidgetFormInputText(),
      'num'      => new sfWidgetFormInputText(),
      'note'     => new sfWidgetFormInputText(),
      'date'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'      => new sfValidatorInteger(array('required' => false)),
      'admin_id' => new sfValidatorInteger(array('required' => false)),
      'type'     => new sfValidatorInteger(array('required' => false)),
      'num'      => new sfValidatorNumber(array('required' => false)),
      'note'     => new sfValidatorString(array('max_length' => 100)),
      'date'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_business_creditlog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBusinessCreditlog';
  }

}
