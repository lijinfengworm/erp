<?php

/**
 * omArticle filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseomArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'img_url'      => new sfWidgetFormFilterInput(),
      'title'        => new sfWidgetFormFilterInput(),
      'redirect_url' => new sfWidgetFormFilterInput(),
      'description'  => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'omMatch_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('omMatch'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'img_url'      => new sfValidatorPass(array('required' => false)),
      'title'        => new sfValidatorPass(array('required' => false)),
      'redirect_url' => new sfValidatorPass(array('required' => false)),
      'description'  => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'omMatch_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('omMatch'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('om_article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'omArticle';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'img_url'      => 'Text',
      'title'        => 'Text',
      'redirect_url' => 'Text',
      'description'  => 'Text',
      'type'         => 'Number',
      'omMatch_id'   => 'ForeignKey',
    );
  }
}
