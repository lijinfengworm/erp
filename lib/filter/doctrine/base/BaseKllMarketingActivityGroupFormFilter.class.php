<?php

/**
 * KllMarketingActivityGroup filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMarketingActivityGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'activity_id' => new sfWidgetFormFilterInput(),
      'item_id'     => new sfWidgetFormFilterInput(),
      'stime'       => new sfWidgetFormFilterInput(),
      'etime'       => new sfWidgetFormFilterInput(),
      'version'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'activity_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'etime'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'version'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_marketing_activity_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMarketingActivityGroup';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'activity_id' => 'Number',
      'item_id'     => 'Number',
      'stime'       => 'Number',
      'etime'       => 'Number',
      'version'     => 'Number',
    );
  }
}
