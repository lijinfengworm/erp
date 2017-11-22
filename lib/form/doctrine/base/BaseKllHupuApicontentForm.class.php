<?php

/**
 * KllHupuApicontent form base class.
 *
 * @method KllHupuApicontent getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllHupuApicontentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kll_hupu_id'       => new sfWidgetFormInputHidden(),
      'kll_hupu_title'    => new sfWidgetFormInputText(),
      'kll_hupu_subtitle' => new sfWidgetFormInputText(),
      'kll_hupu_imgpath'  => new sfWidgetFormTextarea(),
      'kll_hupu_time'     => new sfWidgetFormDateTime(),
      'kll_hupu_url'      => new sfWidgetFormInputText(),
      'kll_hupu_type'     => new sfWidgetFormInputText(),
      'kll_hupu_status'   => new sfWidgetFormInputText(),
      'kll_hupu_origin'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'kll_hupu_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('kll_hupu_id')), 'empty_value' => $this->getObject()->get('kll_hupu_id'), 'required' => false)),
      'kll_hupu_title'    => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'kll_hupu_subtitle' => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'kll_hupu_imgpath'  => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'kll_hupu_time'     => new sfValidatorDateTime(array('required' => false)),
      'kll_hupu_url'      => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'kll_hupu_type'     => new sfValidatorInteger(array('required' => false)),
      'kll_hupu_status'   => new sfValidatorInteger(array('required' => false)),
      'kll_hupu_origin'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_hupu_apicontent[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllHupuApicontent';
  }

}
