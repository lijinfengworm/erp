<?php

/**
 * ZyVideo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZyVideoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'vid'         => new sfWidgetFormFilterInput(),
      'title'       => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'localcover'  => new sfWidgetFormFilterInput(),
      'cover'       => new sfWidgetFormFilterInput(),
      'dateline'    => new sfWidgetFormFilterInput(),
      'playtime'    => new sfWidgetFormFilterInput(),
      'from_url'    => new sfWidgetFormFilterInput(),
      'good_count'  => new sfWidgetFormFilterInput(),
      'author'      => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'vid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'       => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'localcover'  => new sfValidatorPass(array('required' => false)),
      'cover'       => new sfValidatorPass(array('required' => false)),
      'dateline'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'playtime'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'from_url'    => new sfValidatorPass(array('required' => false)),
      'good_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author'      => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zy_video_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZyVideo';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'vid'         => 'Number',
      'title'       => 'Text',
      'description' => 'Text',
      'localcover'  => 'Text',
      'cover'       => 'Text',
      'dateline'    => 'Number',
      'playtime'    => 'Number',
      'from_url'    => 'Text',
      'good_count'  => 'Number',
      'author'      => 'Text',
      'status'      => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
