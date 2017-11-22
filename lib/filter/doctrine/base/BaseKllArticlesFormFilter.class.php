<?php

/**
 * KllArticles filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllArticlesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cid'         => new sfWidgetFormFilterInput(),
      'seo_id'      => new sfWidgetFormFilterInput(),
      'h_id'        => new sfWidgetFormFilterInput(),
      'author'      => new sfWidgetFormFilterInput(),
      'public_time' => new sfWidgetFormFilterInput(),
      'relate_gid'  => new sfWidgetFormFilterInput(),
      'label'       => new sfWidgetFormFilterInput(),
      'title'       => new sfWidgetFormFilterInput(),
      'type'        => new sfWidgetFormFilterInput(),
      'abstract'    => new sfWidgetFormFilterInput(),
      'content'     => new sfWidgetFormFilterInput(),
      'add_time'    => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
      'is_use'      => new sfWidgetFormFilterInput(),
      'audit_uid'   => new sfWidgetFormFilterInput(),
      'audit_time'  => new sfWidgetFormFilterInput(),
      'is_original' => new sfWidgetFormFilterInput(),
      'is_video'    => new sfWidgetFormFilterInput(),
      'order'       => new sfWidgetFormFilterInput(),
      'talent_id'   => new sfWidgetFormFilterInput(),
      'index_order' => new sfWidgetFormFilterInput(),
      'source'      => new sfWidgetFormFilterInput(),
      'platform'    => new sfWidgetFormFilterInput(),
      'cover'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'seo_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'h_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author'      => new sfValidatorPass(array('required' => false)),
      'public_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'relate_gid'  => new sfValidatorPass(array('required' => false)),
      'label'       => new sfValidatorPass(array('required' => false)),
      'title'       => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorPass(array('required' => false)),
      'abstract'    => new sfValidatorPass(array('required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
      'add_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_use'      => new sfValidatorPass(array('required' => false)),
      'audit_uid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'audit_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_original' => new sfValidatorPass(array('required' => false)),
      'is_video'    => new sfValidatorPass(array('required' => false)),
      'order'       => new sfValidatorPass(array('required' => false)),
      'talent_id'   => new sfValidatorPass(array('required' => false)),
      'index_order' => new sfValidatorPass(array('required' => false)),
      'source'      => new sfValidatorPass(array('required' => false)),
      'platform'    => new sfValidatorPass(array('required' => false)),
      'cover'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_articles_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllArticles';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'cid'         => 'Number',
      'seo_id'      => 'Number',
      'h_id'        => 'Number',
      'author'      => 'Text',
      'public_time' => 'Number',
      'relate_gid'  => 'Text',
      'label'       => 'Text',
      'title'       => 'Text',
      'type'        => 'Text',
      'abstract'    => 'Text',
      'content'     => 'Text',
      'add_time'    => 'Number',
      'update_time' => 'Number',
      'is_use'      => 'Text',
      'audit_uid'   => 'Number',
      'audit_time'  => 'Number',
      'is_original' => 'Text',
      'is_video'    => 'Text',
      'order'       => 'Text',
      'talent_id'   => 'Text',
      'index_order' => 'Text',
      'source'      => 'Text',
      'platform'    => 'Text',
      'cover'       => 'Text',
    );
  }
}
