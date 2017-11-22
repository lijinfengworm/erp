<?php

/**
 * wpOrder filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_order_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wporder'), 'add_empty' => true)),
      'source_type'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'create_user_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'create_user_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'target_user_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'target_user_name'  => new sfWidgetFormFilterInput(),
      'role_user_id'      => new sfWidgetFormFilterInput(),
      'role_user_name'    => new sfWidgetFormFilterInput(),
      'wpgame_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpgame'), 'add_empty' => true)),
      'wpserver_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpserver'), 'add_empty' => true)),
      'wppayment_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wppayment'), 'add_empty' => true)),
      'order_no'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'external_order_no' => new sfWidgetFormFilterInput(),
      'amount'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'request_url'       => new sfWidgetFormFilterInput(),
      'recharge_type'     => new sfWidgetFormFilterInput(),
      'ip'                => new sfWidgetFormFilterInput(),
      'error_code'        => new sfWidgetFormFilterInput(),
      'wpgamecard_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpgamecard'), 'add_empty' => true)),
      'transparent'       => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'parent_order_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wporder'), 'column' => 'id')),
      'source_type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_user_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_user_name'  => new sfValidatorPass(array('required' => false)),
      'target_user_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'target_user_name'  => new sfValidatorPass(array('required' => false)),
      'role_user_id'      => new sfValidatorPass(array('required' => false)),
      'role_user_name'    => new sfValidatorPass(array('required' => false)),
      'wpgame_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpgame'), 'column' => 'id')),
      'wpserver_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpserver'), 'column' => 'id')),
      'wppayment_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wppayment'), 'column' => 'id')),
      'order_no'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'external_order_no' => new sfValidatorPass(array('required' => false)),
      'amount'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'request_url'       => new sfValidatorPass(array('required' => false)),
      'recharge_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ip'                => new sfValidatorPass(array('required' => false)),
      'error_code'        => new sfValidatorPass(array('required' => false)),
      'wpgamecard_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpgamecard'), 'column' => 'id')),
      'transparent'       => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('wp_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpOrder';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'parent_order_id'   => 'ForeignKey',
      'source_type'       => 'Number',
      'status'            => 'Number',
      'create_user_id'    => 'Number',
      'create_user_name'  => 'Text',
      'target_user_id'    => 'Number',
      'target_user_name'  => 'Text',
      'role_user_id'      => 'Text',
      'role_user_name'    => 'Text',
      'wpgame_id'         => 'ForeignKey',
      'wpserver_id'       => 'ForeignKey',
      'wppayment_id'      => 'ForeignKey',
      'order_no'          => 'Number',
      'external_order_no' => 'Text',
      'amount'            => 'Number',
      'request_url'       => 'Text',
      'recharge_type'     => 'Number',
      'ip'                => 'Text',
      'error_code'        => 'Text',
      'wpgamecard_id'     => 'ForeignKey',
      'transparent'       => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
