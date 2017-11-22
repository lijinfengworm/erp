<?php

/**
 * TrdShoutao filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdShoutaoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tid'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'item_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'item_url'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pic'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'recommend'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tags'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'        => new sfWidgetFormFilterInput(),
      'send_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'content_img' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'admin_id'    => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'tid'         => new sfValidatorPass(array('required' => false)),
      'title'       => new sfValidatorPass(array('required' => false)),
      'item_id'     => new sfValidatorPass(array('required' => false)),
      'item_url'    => new sfValidatorPass(array('required' => false)),
      'pic'         => new sfValidatorPass(array('required' => false)),
      'recommend'   => new sfValidatorPass(array('required' => false)),
      'tags'        => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'content_img' => new sfValidatorPass(array('required' => false)),
      'admin_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_shoutao_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShoutao';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'tid'         => 'Text',
      'title'       => 'Text',
      'item_id'     => 'Text',
      'item_url'    => 'Text',
      'pic'         => 'Text',
      'recommend'   => 'Text',
      'tags'        => 'Text',
      'type'        => 'Number',
      'send_time'   => 'Date',
      'content_img' => 'Text',
      'admin_id'    => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
