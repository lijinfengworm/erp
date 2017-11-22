<?php

/**
 * TrdUserRecommend form base class.
 *
 * @method TrdUserRecommend getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTrdUserRecommendForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'user_id'           => new sfWidgetFormInputText(),
      'recommend_content' => new sfWidgetFormInputText(),
      'recommend_type'    => new sfWidgetFormInputText(),
      'recommend_id'      => new sfWidgetFormInputText(),
      'create_time'       => new sfWidgetFormInputText(),
      'is_delete'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'           => new sfValidatorInteger(array('required' => false)),
      'recommend_content' => new sfValidatorString(array('max_length' => 255)),
      'recommend_type'    => new sfValidatorInteger(array('required' => false)),
      'recommend_id'      => new sfValidatorInteger(array('required' => false)),
      'create_time'       => new sfValidatorPass(array('required' => false)),
      'is_delete'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_user_recommend[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserRecommend';
  }

}
