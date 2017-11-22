<?php

/**
 * KllCouponsDetail form base class.
 *
 * @method KllCouponsDetail getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllCouponsDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'account'     => new sfWidgetFormInputText(),
      'stime'       => new sfWidgetFormInputText(),
      'etime'       => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'activity_id' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'account'     => new sfValidatorString(array('max_length' => 30)),
      'stime'       => new sfValidatorInteger(array('required' => false)),
      'etime'       => new sfValidatorInteger(array('required' => false)),
      'status'      => new sfValidatorInteger(array('required' => false)),
      'activity_id' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('kll_coupons_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCouponsDetail';
  }

}
