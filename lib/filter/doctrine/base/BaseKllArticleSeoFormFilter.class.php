<?php

/**
 * KllArticleSeo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllArticleSeoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'keywords'    => new sfWidgetFormFilterInput(),
      'title'       => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'uid_opt'     => new sfWidgetFormFilterInput(),
      'add_time'    => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'keywords'    => new sfValidatorPass(array('required' => false)),
      'title'       => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'uid_opt'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'add_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_article_seo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticleSeo';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'keywords'    => 'Text',
      'title'       => 'Text',
      'description' => 'Text',
      'uid_opt'     => 'Number',
      'add_time'    => 'Number',
      'update_time' => 'Number',
    );
  }
}
