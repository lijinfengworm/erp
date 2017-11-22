<?php

/**
 * TrdSpecial filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'            => new sfWidgetFormFilterInput(),
      'name'            => new sfWidgetFormFilterInput(),
      'm_title'         => new sfWidgetFormFilterInput(),
      'journal_title'   => new sfWidgetFormFilterInput(),
      'journal_desc'    => new sfWidgetFormFilterInput(),
      'journal_img'     => new sfWidgetFormFilterInput(),
      'journal_type_id' => new sfWidgetFormFilterInput(),
      'show_journal'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cateid'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdSpecialCate'), 'add_empty' => true)),
      'remarks'         => new sfWidgetFormFilterInput(),
      'is_theme'        => new sfWidgetFormFilterInput(),
      'theme_id'        => new sfWidgetFormFilterInput(),
      'template'        => new sfWidgetFormFilterInput(),
      'info'            => new sfWidgetFormFilterInput(),
      'support'         => new sfWidgetFormFilterInput(),
      'agaist'          => new sfWidgetFormFilterInput(),
      'comment_count'   => new sfWidgetFormFilterInput(),
      'click_count'     => new sfWidgetFormFilterInput(),
      'special_status'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'timing_interval' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'type'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'            => new sfValidatorPass(array('required' => false)),
      'm_title'         => new sfValidatorPass(array('required' => false)),
      'journal_title'   => new sfValidatorPass(array('required' => false)),
      'journal_desc'    => new sfValidatorPass(array('required' => false)),
      'journal_img'     => new sfValidatorPass(array('required' => false)),
      'journal_type_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_journal'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cateid'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TrdSpecialCate'), 'column' => 'id')),
      'remarks'         => new sfValidatorPass(array('required' => false)),
      'is_theme'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'theme_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'template'        => new sfValidatorPass(array('required' => false)),
      'info'            => new sfValidatorPass(array('required' => false)),
      'support'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agaist'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'click_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'special_status'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'timing_interval' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_special_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecial';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'type'            => 'Number',
      'name'            => 'Text',
      'm_title'         => 'Text',
      'journal_title'   => 'Text',
      'journal_desc'    => 'Text',
      'journal_img'     => 'Text',
      'journal_type_id' => 'Number',
      'show_journal'    => 'Number',
      'cateid'          => 'ForeignKey',
      'remarks'         => 'Text',
      'is_theme'        => 'Number',
      'theme_id'        => 'Number',
      'template'        => 'Text',
      'info'            => 'Text',
      'support'         => 'Number',
      'agaist'          => 'Number',
      'comment_count'   => 'Number',
      'click_count'     => 'Number',
      'special_status'  => 'Number',
      'timing_interval' => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'deleted_at'      => 'Date',
    );
  }
}
