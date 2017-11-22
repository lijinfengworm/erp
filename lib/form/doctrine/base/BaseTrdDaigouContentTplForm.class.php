<?php

/**
 * TrdDaigouContentTpl form base class.
 *
 * @method TrdDaigouContentTpl getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouContentTplForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'root_id'     => new sfWidgetFormInputText(),
      'children_id' => new sfWidgetFormInputText(),
      'template'    => new sfWidgetFormTextarea(),
      'status'      => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'root_id'     => new sfValidatorInteger(array('required' => false)),
      'children_id' => new sfValidatorInteger(array('required' => false)),
      'template'    => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_content_tpl[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouContentTpl';
  }

}
