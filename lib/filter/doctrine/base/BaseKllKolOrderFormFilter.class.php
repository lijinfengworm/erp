<?php

/**
 * KllKolOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'     => new sfWidgetFormFilterInput(),
      'sub_order_id'     => new sfWidgetFormFilterInput(),
      'order_time'       => new sfWidgetFormFilterInput(),
      'total_price'      => new sfWidgetFormFilterInput(),
      'main_total_price' => new sfWidgetFormFilterInput(),
      'is_new_custom'    => new sfWidgetFormFilterInput(),
      'channel'          => new sfWidgetFormFilterInput(),
      'item_id'          => new sfWidgetFormFilterInput(),
      'item_title'       => new sfWidgetFormFilterInput(),
      'commision'        => new sfWidgetFormFilterInput(),
      'commision_rate'   => new sfWidgetFormFilterInput(),
      'kol_id'           => new sfWidgetFormFilterInput(),
      'user_id'          => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'flag'             => new sfWidgetFormFilterInput(),
      'user_name'        => new sfWidgetFormFilterInput(),
      'channel_id'       => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'     => new sfValidatorPass(array('required' => false)),
      'sub_order_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'main_total_price' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'is_new_custom'    => new sfValidatorPass(array('required' => false)),
      'channel'          => new sfValidatorPass(array('required' => false)),
      'item_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_title'       => new sfValidatorPass(array('required' => false)),
      'commision'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commision_rate'   => new sfValidatorPass(array('required' => false)),
      'kol_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'           => new sfValidatorPass(array('required' => false)),
      'flag'             => new sfValidatorPass(array('required' => false)),
      'user_name'        => new sfValidatorPass(array('required' => false)),
      'channel_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolOrder';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'order_number'     => 'Text',
      'sub_order_id'     => 'Number',
      'order_time'       => 'Number',
      'total_price'      => 'Number',
      'main_total_price' => 'Number',
      'is_new_custom'    => 'Text',
      'channel'          => 'Text',
      'item_id'          => 'Number',
      'item_title'       => 'Text',
      'commision'        => 'Number',
      'commision_rate'   => 'Text',
      'kol_id'           => 'Number',
      'user_id'          => 'Number',
      'status'           => 'Text',
      'flag'             => 'Text',
      'user_name'        => 'Text',
      'channel_id'       => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
