<?php

/**
 * KllMarketingActivity filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMarketingActivityFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'           => new sfWidgetFormFilterInput(),
      'mode'            => new sfWidgetFormFilterInput(),
      'scope'           => new sfWidgetFormFilterInput(),
      'attr1'           => new sfWidgetFormFilterInput(),
      'attr2'           => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'stime'           => new sfWidgetFormFilterInput(),
      'etime'           => new sfWidgetFormFilterInput(),
      'intro'           => new sfWidgetFormFilterInput(),
      'group_id'        => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'type_limit'      => new sfWidgetFormFilterInput(),
      'url'             => new sfWidgetFormFilterInput(),
      'new_version'     => new sfWidgetFormFilterInput(),
      'current_version' => new sfWidgetFormFilterInput(),
      'ing_version'     => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'           => new sfValidatorPass(array('required' => false)),
      'mode'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'scope'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr1'           => new sfValidatorPass(array('required' => false)),
      'attr2'           => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'intro'           => new sfValidatorPass(array('required' => false)),
      'group_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type_limit'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url'             => new sfValidatorPass(array('required' => false)),
      'new_version'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'current_version' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ing_version'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_marketing_activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMarketingActivity';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'title'           => 'Text',
      'mode'            => 'Number',
      'scope'           => 'Number',
      'attr1'           => 'Text',
      'attr2'           => 'Text',
      'status'          => 'Number',
      'stime'           => 'Number',
      'etime'           => 'Number',
      'intro'           => 'Text',
      'group_id'        => 'Number',
      'type'            => 'Number',
      'type_limit'      => 'Number',
      'url'             => 'Text',
      'new_version'     => 'Number',
      'current_version' => 'Number',
      'ing_version'     => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
