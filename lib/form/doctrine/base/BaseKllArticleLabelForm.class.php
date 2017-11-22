<?php

/**
 * KllArticleLabel form base class.
 *
 * @method KllArticleLabel getObject() Returns the current form's model object
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKllArticleLabelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'is_tree'     => new sfWidgetFormInputText(),
      'fa'          => new sfWidgetFormInputText(),
      'name'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'opt_uid'     => new sfWidgetFormInputText(),
      'add_time'    => new sfWidgetFormInputText(),
      'update_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'is_tree'     => new sfValidatorPass(array('required' => false)),
      'fa'          => new sfValidatorInteger(array('required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'opt_uid'     => new sfValidatorInteger(array('required' => false)),
      'add_time'    => new sfValidatorInteger(array('required' => false)),
      'update_time' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_article_label[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticleLabel';
  }

}
