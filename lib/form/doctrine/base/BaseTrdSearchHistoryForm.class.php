<?php

/**
 * TrdSearchHistory form base class.
 *
 * @method TrdSearchHistory getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdSearchHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'word'        => new sfWidgetFormInputText(),
      'count'       => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'source'      => new sfWidgetFormInputText(),
      'time'        => new sfWidgetFormInputText(),
      'create_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'word'        => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'count'       => new sfValidatorInteger(array('required' => false)),
      'type'        => new sfValidatorInteger(array('required' => false)),
      'source'      => new sfValidatorInteger(array('required' => false)),
      'time'        => new sfValidatorInteger(array('required' => false)),
      'create_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_search_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSearchHistory';
  }

}
