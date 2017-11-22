<?php

/**
 * KllOperateSpecial form base class.
 *
 * @method KllOperateSpecial getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllOperateSpecialForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'title'    => new sfWidgetFormInputText(),
      'url'      => new sfWidgetFormInputText(),
      'position' => new sfWidgetFormInputText(),
      'order'    => new sfWidgetFormInputText(),
      'opt_uid'  => new sfWidgetFormInputText(),
      'add_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'position' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order'    => new sfValidatorPass(array('required' => false)),
      'opt_uid'  => new sfValidatorPass(array('required' => false)),
      'add_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_operate_special[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOperateSpecial';
  }

}
