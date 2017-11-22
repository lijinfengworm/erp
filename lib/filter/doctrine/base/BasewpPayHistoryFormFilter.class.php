<?php

/**
 * wpPayHistory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpPayHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wp_game_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpGame'), 'add_empty' => true)),
      'wp_server_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpServer'), 'add_empty' => true)),
      'prorate_period'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'date'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'successful_pay_people' => new sfWidgetFormFilterInput(),
      'total_pay_people'      => new sfWidgetFormFilterInput(),
      'successful_pay_order'  => new sfWidgetFormFilterInput(),
      'total_pay_order'       => new sfWidgetFormFilterInput(),
      'transfer_pay_amount'   => new sfWidgetFormFilterInput(),
      'total_pay_amount'      => new sfWidgetFormFilterInput(),
      'average_pay_amount'    => new sfWidgetFormFilterInput(),
      'bankcard_pay_amount'   => new sfWidgetFormFilterInput(),
      'alipay_pay_amount'     => new sfWidgetFormFilterInput(),
      'phonecard_pay_amount'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'wp_game_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpGame'), 'column' => 'id')),
      'wp_server_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpServer'), 'column' => 'id')),
      'prorate_period'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'date'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'successful_pay_people' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_pay_people'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'successful_pay_order'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_pay_order'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'transfer_pay_amount'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_pay_amount'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'average_pay_amount'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'bankcard_pay_amount'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'alipay_pay_amount'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'phonecard_pay_amount'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('wp_pay_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpPayHistory';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'wp_game_id'            => 'ForeignKey',
      'wp_server_id'          => 'ForeignKey',
      'prorate_period'        => 'Date',
      'date'                  => 'Date',
      'successful_pay_people' => 'Number',
      'total_pay_people'      => 'Number',
      'successful_pay_order'  => 'Number',
      'total_pay_order'       => 'Number',
      'transfer_pay_amount'   => 'Number',
      'total_pay_amount'      => 'Number',
      'average_pay_amount'    => 'Number',
      'bankcard_pay_amount'   => 'Number',
      'alipay_pay_amount'     => 'Number',
      'phonecard_pay_amount'  => 'Number',
    );
  }
}
