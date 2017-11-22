<?php

/**
 * KllTrainingprogram form base class.
 *
 * @method KllTrainingprogram getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllTrainingprogramForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'title'       => new sfWidgetFormInputText(),
      'author'      => new sfWidgetFormInputText(),
      'cover'       => new sfWidgetFormInputText(),
      'articles'    => new sfWidgetFormInputText(),
      'order'       => new sfWidgetFormInputText(),
      'h_id'        => new sfWidgetFormInputText(),
      'public_time' => new sfWidgetFormInputText(),
      'category'    => new sfWidgetFormInputText(),
      'abstract'    => new sfWidgetFormInputText(),
      'content'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'author'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cover'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'articles'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'order'       => new sfValidatorPass(array('required' => false)),
      'h_id'        => new sfValidatorPass(array('required' => false)),
      'public_time' => new sfValidatorPass(array('required' => false)),
      'category'    => new sfValidatorPass(array('required' => false)),
      'abstract'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_trainingprogram[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllTrainingprogram';
  }

}
