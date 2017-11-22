<?php

/**
 * TrdGoodsAddress filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsAddressFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'activity_id'   => new sfWidgetFormFilterInput(),
      'hupu_uid'      => new sfWidgetFormFilterInput(),
      'hupu_username' => new sfWidgetFormFilterInput(),
      'name'          => new sfWidgetFormFilterInput(),
      'tel'           => new sfWidgetFormFilterInput(),
      'province'      => new sfWidgetFormFilterInput(),
      'city'          => new sfWidgetFormFilterInput(),
      'address'       => new sfWidgetFormFilterInput(),
      'note'          => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'activity_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username' => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'tel'           => new sfValidatorPass(array('required' => false)),
      'province'      => new sfValidatorPass(array('required' => false)),
      'city'          => new sfValidatorPass(array('required' => false)),
      'address'       => new sfValidatorPass(array('required' => false)),
      'note'          => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_address_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsAddress';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'activity_id'   => 'Number',
      'hupu_uid'      => 'Number',
      'hupu_username' => 'Text',
      'name'          => 'Text',
      'tel'           => 'Text',
      'province'      => 'Text',
      'city'          => 'Text',
      'address'       => 'Text',
      'note'          => 'Text',
      'status'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
