<?php

/**
 * TrdProductTag filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdProductTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hits'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hot'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_order'    => new sfWidgetFormFilterInput(),
      'hidden'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'trd_news_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TrdNews')),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'hits'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hot'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_order'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hidden'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'trd_news_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TrdNews', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_product_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function add
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in /data/wwwroot/hupu/kaluli-erp-project/lib/util/sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in /data/wwwroot/hupu/kaluli-erp-project/lib/util/sfToolkit.class.php on line 362
TrdNewsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.TrdNewsTag TrdNewsTag')
      ->andWhereIn('TrdNewsTag.trd_news_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'TrdProductTag';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'name'          => 'Text',
      'hits'          => 'Number',
      'hot'           => 'Number',
      'show_order'    => 'Number',
      'hidden'        => 'Boolean',
      'trd_news_list' => 'ManyKey',
    );
  }
}
