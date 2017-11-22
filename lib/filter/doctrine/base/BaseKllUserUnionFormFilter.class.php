<?php

/**
 * KllUserUnion filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllUserUnionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'union_id'        => new sfWidgetFormFilterInput(),
      'info'            => new sfWidgetFormFilterInput(),
      'union_user_name' => new sfWidgetFormFilterInput(),
      'ct_time'         => new sfWidgetFormFilterInput(),
      'up_time'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'            => new sfValidatorPass(array('required' => false)),
      'union_id'        => new sfValidatorPass(array('required' => false)),
      'info'            => new sfValidatorPass(array('required' => false)),
      'union_user_name' => new sfValidatorPass(array('required' => false)),
      'ct_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'up_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_user_union_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUserUnion';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'user_id'         => 'Number',
      'type'            => 'Text',
      'union_id'        => 'Text',
      'info'            => 'Text',
      'union_user_name' => 'Text',
      'ct_time'         => 'Number',
      'up_time'         => 'Number',
    );
  }
}
