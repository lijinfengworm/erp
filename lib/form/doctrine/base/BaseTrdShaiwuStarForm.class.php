<?php

/**
 * TrdShaiwuStar form base class.
 *
 * @method TrdShaiwuStar getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdShaiwuStarForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'uid'            => new sfWidgetFormInputText(),
      'username'       => new sfWidgetFormInputText(),
      'shaiwu_num'     => new sfWidgetFormInputText(),
      'shaiwu_hot_num' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'uid'            => new sfValidatorInteger(array('required' => false)),
      'username'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'shaiwu_num'     => new sfValidatorInteger(array('required' => false)),
      'shaiwu_hot_num' => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('trd_shaiwu_star[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShaiwuStar';
  }

}
