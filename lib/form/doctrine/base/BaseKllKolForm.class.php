<?php

/**
 * KllKol form base class.
 *
 * @method KllKol getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllKolForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormInputText(),
      'abstract'    => new sfWidgetFormInputText(),
      'home_page'   => new sfWidgetFormInputText(),
      'channel_id'  => new sfWidgetFormInputText(),
      'benefits_id' => new sfWidgetFormInputText(),
      'account'     => new sfWidgetFormInputText(),
      'status'      => new sfWidgetFormInputText(),
      'commision'   => new sfWidgetFormInputText(),
      'remark'      => new sfWidgetFormTextarea(),
      'ct_time'     => new sfWidgetFormDateTime(),
      'user_name'   => new sfWidgetFormInputText(),
      'mobile'      => new sfWidgetFormInputText(),
      'nick_name'   => new sfWidgetFormInputText(),
      'head_image'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'     => new sfValidatorInteger(array('required' => false)),
      'abstract'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'home_page'   => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'channel_id'  => new sfValidatorPass(array('required' => false)),
      'benefits_id' => new sfValidatorPass(array('required' => false)),
      'account'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'status'      => new sfValidatorPass(array('required' => false)),
      'commision'   => new sfValidatorPass(array('required' => false)),
      'remark'      => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'ct_time'     => new sfValidatorDateTime(array('required' => false)),
      'user_name'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mobile'      => new sfValidatorPass(array('required' => false)),
      'nick_name'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'head_image'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKol';
  }

}
