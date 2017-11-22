<?php

/**
 * ZyObject filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyObjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'fullname'     => new sfWidgetFormFilterInput(),
      'group_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Group'), 'add_empty' => true)),
      'shortcut'     => new sfWidgetFormFilterInput(),
      'member_count' => new sfWidgetFormFilterInput(),
      'new_count'    => new sfWidgetFormFilterInput(),
      'join_count'   => new sfWidgetFormFilterInput(),
      'voice_count'  => new sfWidgetFormFilterInput(),
      'memo'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bbs_url'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hupu_uid'     => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'         => new sfValidatorPass(array('required' => false)),
      'fullname'     => new sfValidatorPass(array('required' => false)),
      'group_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Group'), 'column' => 'id')),
      'shortcut'     => new sfValidatorPass(array('required' => false)),
      'member_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'new_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'join_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'voice_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'memo'         => new sfValidatorPass(array('required' => false)),
      'logo'         => new sfValidatorPass(array('required' => false)),
      'bbs_url'      => new sfValidatorPass(array('required' => false)),
      'hupu_uid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_object_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyObject';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'name'         => 'Text',
      'fullname'     => 'Text',
      'group_id'     => 'ForeignKey',
      'shortcut'     => 'Text',
      'member_count' => 'Number',
      'new_count'    => 'Number',
      'join_count'   => 'Number',
      'voice_count'  => 'Number',
      'memo'         => 'Text',
      'logo'         => 'Text',
      'bbs_url'      => 'Text',
      'hupu_uid'     => 'Number',
      'status'       => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
