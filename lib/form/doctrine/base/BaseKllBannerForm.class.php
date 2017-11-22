<?php

/**
 * KllBanner form base class.
 *
 * @method KllBanner getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllBannerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'att_id'   => new sfWidgetFormInputText(),
      'title'    => new sfWidgetFormInputText(),
      'abstract' => new sfWidgetFormInputText(),
      'add_time' => new sfWidgetFormInputText(),
      'url'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'att_id'   => new sfValidatorPass(array('required' => false)),
      'title'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'abstract' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'add_time' => new sfValidatorInteger(array('required' => false)),
      'url'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_banner[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBanner';
  }

}
