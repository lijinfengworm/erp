<?php

/**
 * KllArticleLabel filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllArticleLabelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'is_tree'     => new sfWidgetFormFilterInput(),
      'fa'          => new sfWidgetFormFilterInput(),
      'name'        => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'opt_uid'     => new sfWidgetFormFilterInput(),
      'add_time'    => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'is_tree'     => new sfValidatorPass(array('required' => false)),
      'fa'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'        => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'opt_uid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'add_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_article_label_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticleLabel';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'is_tree'     => 'Text',
      'fa'          => 'Number',
      'name'        => 'Text',
      'description' => 'Text',
      'opt_uid'     => 'Number',
      'add_time'    => 'Number',
      'update_time' => 'Number',
    );
  }
}
