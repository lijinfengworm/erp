<?php

/**
 * TrdAccountHistory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAccountHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'category'        => new sfWidgetFormFilterInput(),
      'source'          => new sfWidgetFormFilterInput(),
      'explanation'     => new sfWidgetFormFilterInput(),
      'actionid'        => new sfWidgetFormFilterInput(),
      'integral'        => new sfWidgetFormFilterInput(),
      'gold'            => new sfWidgetFormFilterInput(),
      'before_integral' => new sfWidgetFormFilterInput(),
      'before_gold'     => new sfWidgetFormFilterInput(),
      'after_integral'  => new sfWidgetFormFilterInput(),
      'after_gold'      => new sfWidgetFormFilterInput(),
      'grant_uid'       => new sfWidgetFormFilterInput(),
      'grant_username'  => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'          => new sfValidatorPass(array('required' => false)),
      'explanation'     => new sfValidatorPass(array('required' => false)),
      'actionid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'integral'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gold'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'before_integral' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'before_gold'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'after_integral'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'after_gold'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'  => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_account_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAccountHistory';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'type'            => 'Number',
      'category'        => 'Number',
      'source'          => 'Text',
      'explanation'     => 'Text',
      'actionid'        => 'Number',
      'integral'        => 'Number',
      'gold'            => 'Number',
      'before_integral' => 'Number',
      'before_gold'     => 'Number',
      'after_integral'  => 'Number',
      'after_gold'      => 'Number',
      'grant_uid'       => 'Number',
      'grant_username'  => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
