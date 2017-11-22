<?php

/**
 * TrdNoticesCount form base class.
 *
 * @method TrdNoticesCount getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNoticesCountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'uid'   => new sfWidgetFormInputText(),
      'type1' => new sfWidgetFormInputText(),
      'type2' => new sfWidgetFormInputText(),
      'type3' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'   => new sfValidatorInteger(array('required' => false)),
      'type1' => new sfValidatorInteger(array('required' => false)),
      'type2' => new sfValidatorInteger(array('required' => false)),
      'type3' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_notices_count[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNoticesCount';
  }

}
