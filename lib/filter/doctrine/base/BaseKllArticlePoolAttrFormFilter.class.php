<?php

/**
 * KllArticlePoolAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllArticlePoolAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'article_id' => new sfWidgetFormFilterInput(),
      'content'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'article_id' => new sfValidatorPass(array('required' => false)),
      'content'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_article_pool_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticlePoolAttr';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'article_id' => 'Text',
      'content'    => 'Text',
    );
  }
}
