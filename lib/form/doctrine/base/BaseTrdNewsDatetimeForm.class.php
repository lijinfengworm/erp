<?php

/**
 * TrdNewsDatetime form base class.
 *
 * @method TrdNewsDatetime getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdNewsDatetimeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'newsid'       => new sfWidgetFormInputText(),
      'publish_date' => new sfWidgetFormDateTime(),
      'update_date'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'newsid'       => new sfValidatorInteger(),
      'publish_date' => new sfValidatorDateTime(),
      'update_date'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_news_datetime[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNewsDatetime';
  }

}
