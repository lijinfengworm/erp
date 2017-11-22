<?php

/**
 * TrdDaigouMenu form base class.
 *
 * @method TrdDaigouMenu getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouMenuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'root_id' => new sfWidgetFormInputText(),
      'name'    => new sfWidgetFormInputText(),
      'type'    => new sfWidgetFormInputText(),
      'pic_url' => new sfWidgetFormInputText(),
      'sort'    => new sfWidgetFormInputText(),
      'lft'     => new sfWidgetFormInputText(),
      'rgt'     => new sfWidgetFormInputText(),
      'level'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'root_id' => new sfValidatorInteger(array('required' => false)),
      'name'    => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'type'    => new sfValidatorInteger(array('required' => false)),
      'pic_url' => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'sort'    => new sfValidatorInteger(array('required' => false)),
      'lft'     => new sfValidatorInteger(array('required' => false)),
      'rgt'     => new sfValidatorInteger(array('required' => false)),
      'level'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TrdDaigouMenu', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('trd_daigou_menu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouMenu';
  }

}
