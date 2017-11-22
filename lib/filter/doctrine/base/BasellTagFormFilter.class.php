<?php

/**
 * llTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasellTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(),
      'll_articles_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'llArticle')),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'll_articles_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'llArticle', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ll_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addLlArticlesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.llArticleTag llArticleTag')
      ->andWhereIn('llArticleTag.ll_article_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'llTag';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'll_articles_list' => 'ManyKey',
    );
  }
}
