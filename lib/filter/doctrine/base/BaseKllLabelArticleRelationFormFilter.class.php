<?php

/**
 * KllLabelArticleRelation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllLabelArticleRelationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'article_id' => new sfWidgetFormFilterInput(),
      'label_id'   => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'article_id' => new sfValidatorPass(array('required' => false)),
      'label_id'   => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_label_article_relation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllLabelArticleRelation';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'article_id' => 'Text',
      'label_id'   => 'Text',
      'status'     => 'Text',
    );
  }
}
