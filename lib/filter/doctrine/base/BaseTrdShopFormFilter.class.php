<?php

/**
 * TrdShop filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdShopFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'external_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'owner_name'     => new sfWidgetFormFilterInput(),
      'link'           => new sfWidgetFormFilterInput(),
      'item_count'     => new sfWidgetFormFilterInput(),
      'src'            => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormFilterInput(),
      'ban_start_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'ban_end_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'external_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'           => new sfValidatorPass(array('required' => false)),
      'owner_name'     => new sfValidatorPass(array('required' => false)),
      'link'           => new sfValidatorPass(array('required' => false)),
      'item_count'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'src'            => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ban_start_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'ban_end_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_shop_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShop';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'external_id'    => 'Number',
      'name'           => 'Text',
      'owner_name'     => 'Text',
      'link'           => 'Text',
      'item_count'     => 'Number',
      'src'            => 'Text',
      'status'         => 'Number',
      'ban_start_time' => 'Date',
      'ban_end_time'   => 'Date',
    );
  }
}
