<?php

/**
 * KllAd form base class.
 *
 * @method KllAd getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllAdForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'att_id'   => new sfWidgetFormInputText(),
      'position' => new sfWidgetFormInputText(),
      'opt_uid'  => new sfWidgetFormInputText(),
      'abstract' => new sfWidgetFormInputText(),
      'add_time' => new sfWidgetFormInputText(),
      'url'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'att_id'   => new sfValidatorInteger(array('required' => false)),
      'position' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'opt_uid'  => new sfValidatorInteger(array('required' => false)),
      'abstract' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'add_time' => new sfValidatorInteger(array('required' => false)),
      'url'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_ad[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllAd';
  }

}
