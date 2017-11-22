<?php

/**
 * TrdNewsZhidingLog form base class.
 *
 * @method TrdNewsZhidingLog getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsZhidingLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'newsid'     => new sfWidgetFormInputText(),
      'type'       => new sfWidgetFormInputText(),
      'zhiding_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'newsid'     => new sfValidatorInteger(),
      'type'       => new sfValidatorInteger(),
      'zhiding_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_news_zhiding_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNewsZhidingLog';
  }

}
