<?php

/**
 * KllXbuy form base class.
 *
 * @method KllXbuy getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllXbuyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'title'       => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'detail_url'  => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'start_time'  => new sfWidgetFormInputText(),
      'end_time'    => new sfWidgetFormInputText(),
      'ct_time'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'detail_url'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'start_time'  => new sfValidatorPass(array('required' => false)),
      'end_time'    => new sfValidatorPass(array('required' => false)),
      'ct_time'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_xbuy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllXbuy';
  }

}
