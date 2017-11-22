<?php

/**
 * KaluliArticle form base class.
 *
 * @method KaluliArticle getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKaluliArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'title'          => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'category'       => new sfWidgetFormInputText(),
      'category_child' => new sfWidgetFormInputText(),
      'hits'           => new sfWidgetFormInputText(),
      'intro'          => new sfWidgetFormTextarea(),
      'status'         => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'type'           => new sfValidatorInteger(array('required' => false)),
      'category'       => new sfValidatorInteger(array('required' => false)),
      'category_child' => new sfValidatorInteger(array('required' => false)),
      'hits'           => new sfValidatorInteger(array('required' => false)),
      'intro'          => new sfValidatorString(array('max_length' => 512, 'required' => false)),
      'status'         => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kaluli_article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliArticle';
  }

}
