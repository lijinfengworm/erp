<?php

/**
 * TrdDaigouInventory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdDaigouInventoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'title'         => new sfWidgetFormFilterInput(),
      'intro'         => new sfWidgetFormFilterInput(),
      'front_pic'     => new sfWidgetFormFilterInput(),
      'type_id'       => new sfWidgetFormFilterInput(),
      'like_count'    => new sfWidgetFormFilterInput(),
      'goods_num'     => new sfWidgetFormFilterInput(),
      'goods_info'    => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'title'         => new sfValidatorPass(array('required' => false)),
      'intro'         => new sfValidatorPass(array('required' => false)),
      'front_pic'     => new sfValidatorPass(array('required' => false)),
      'type_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'like_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_num'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'goods_info'    => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_daigou_inventory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdDaigouInventory';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'title'         => 'Text',
      'intro'         => 'Text',
      'front_pic'     => 'Text',
      'type_id'       => 'Number',
      'like_count'    => 'Number',
      'goods_num'     => 'Number',
      'goods_info'    => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
