<?php

/**
 * KllSpecialArticle filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllSpecialArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'special_id' => new sfWidgetFormFilterInput(),
      'article_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'special_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'article_id' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_special_article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSpecialArticle';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'special_id' => 'Number',
      'article_id' => 'Text',
    );
  }
}
