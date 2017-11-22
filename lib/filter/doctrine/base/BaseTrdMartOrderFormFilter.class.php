<?php

/**
 * TrdMartOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdMartOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mart_order_number' => new sfWidgetFormFilterInput(),
      'ibilling_number'   => new sfWidgetFormFilterInput(),
      'pay_price'         => new sfWidgetFormFilterInput(),
      'mart_price'        => new sfWidgetFormFilterInput(),
      'attr'              => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'mart_order_number' => new sfValidatorPass(array('required' => false)),
      'ibilling_number'   => new sfValidatorPass(array('required' => false)),
      'pay_price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'mart_price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'attr'              => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_mart_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMartOrder';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'mart_order_number' => 'Text',
      'ibilling_number'   => 'Text',
      'pay_price'         => 'Number',
      'mart_price'        => 'Number',
      'attr'              => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
