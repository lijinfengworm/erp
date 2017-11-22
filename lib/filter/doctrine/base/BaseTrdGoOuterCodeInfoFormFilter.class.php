<?php

/**
 * TrdGoOuterCodeInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoOuterCodeInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'click_id'         => new sfWidgetFormFilterInput(),
      'uid'              => new sfWidgetFormFilterInput(),
      'username'         => new sfWidgetFormFilterInput(),
      'cooick_id'        => new sfWidgetFormFilterInput(),
      'referer'          => new sfWidgetFormFilterInput(),
      'referer_host'     => new sfWidgetFormFilterInput(),
      'referer_id'       => new sfWidgetFormFilterInput(),
      'destination'      => new sfWidgetFormFilterInput(),
      'destination_host' => new sfWidgetFormFilterInput(),
      'click_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'item_name'        => new sfWidgetFormFilterInput(),
      'item_id'          => new sfWidgetFormFilterInput(),
      'item_price'       => new sfWidgetFormFilterInput(),
      'item_num'         => new sfWidgetFormFilterInput(),
      'item_type'        => new sfWidgetFormFilterInput(),
      'shop_nick'        => new sfWidgetFormFilterInput(),
      'trade_time'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'trade_commission' => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'click_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'uid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'         => new sfValidatorPass(array('required' => false)),
      'cooick_id'        => new sfValidatorPass(array('required' => false)),
      'referer'          => new sfValidatorPass(array('required' => false)),
      'referer_host'     => new sfValidatorPass(array('required' => false)),
      'referer_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'destination'      => new sfValidatorPass(array('required' => false)),
      'destination_host' => new sfValidatorPass(array('required' => false)),
      'click_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'item_name'        => new sfValidatorPass(array('required' => false)),
      'item_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'item_num'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_type'        => new sfValidatorPass(array('required' => false)),
      'shop_nick'        => new sfValidatorPass(array('required' => false)),
      'trade_time'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'trade_commission' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_go_outer_code_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoOuterCodeInfo';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'click_id'         => 'Number',
      'uid'              => 'Number',
      'username'         => 'Text',
      'cooick_id'        => 'Text',
      'referer'          => 'Text',
      'referer_host'     => 'Text',
      'referer_id'       => 'Number',
      'destination'      => 'Text',
      'destination_host' => 'Text',
      'click_time'       => 'Date',
      'item_name'        => 'Text',
      'item_id'          => 'Number',
      'item_price'       => 'Number',
      'item_num'         => 'Number',
      'item_type'        => 'Text',
      'shop_nick'        => 'Text',
      'trade_time'       => 'Date',
      'trade_commission' => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'deleted_at'       => 'Date',
    );
  }
}
