<?php

/**
 * KllWiki form base class.
 *
 * @method KllWiki getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllWikiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'title'          => new sfWidgetFormTextarea(),
      'banner'         => new sfWidgetFormTextarea(),
      'content'        => new sfWidgetFormInputText(),
      'qa'             => new sfWidgetFormTextarea(),
      'relate_article' => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'banner'         => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'content'        => new sfValidatorPass(array('required' => false)),
      'qa'             => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'relate_article' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kll_wiki[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllWiki';
  }

}
