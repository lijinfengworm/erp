<?php

/**
 * KaluliOrderAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliOrderAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number'       => new sfWidgetFormFilterInput(),
      'order_id'           => new sfWidgetFormFilterInput(),
      'code'               => new sfWidgetFormFilterInput(),
      'attr'               => new sfWidgetFormFilterInput(),
      'refund_price'       => new sfWidgetFormFilterInput(),
      'refund_express_fee' => new sfWidgetFormFilterInput(),
      'refund'             => new sfWidgetFormFilterInput(),
      'ware_type'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ware_id'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ware_code'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_number'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code'               => new sfValidatorPass(array('required' => false)),
      'attr'               => new sfValidatorPass(array('required' => false)),
      'refund_price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund_express_fee' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'refund'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'ware_type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ware_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ware_code'          => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_order_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliOrderAttr';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'order_number'       => 'Number',
      'order_id'           => 'Number',
      'code'               => 'Text',
      'attr'               => 'Text',
      'refund_price'       => 'Number',
      'refund_express_fee' => 'Number',
      'refund'             => 'Number',
      'ware_type'          => 'Number',
      'ware_id'            => 'Number',
      'ware_code'          => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
