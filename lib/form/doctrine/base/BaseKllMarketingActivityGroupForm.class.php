<?php

/**
 * KllMarketingActivityGroup form base class.
 *
 * @method KllMarketingActivityGroup getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllMarketingActivityGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'activity_id' => new sfWidgetFormInputText(),
      'item_id'     => new sfWidgetFormInputText(),
      'stime'       => new sfWidgetFormInputText(),
      'etime'       => new sfWidgetFormInputText(),
      'version'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'activity_id' => new sfValidatorInteger(array('required' => false)),
      'item_id'     => new sfValidatorInteger(array('required' => false)),
      'stime'       => new sfValidatorInteger(array('required' => false)),
      'etime'       => new sfValidatorInteger(array('required' => false)),
      'version'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_marketing_activity_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMarketingActivityGroup';
  }

}
