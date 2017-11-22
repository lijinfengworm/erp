<?php

/**
 * transferPlayer filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetransferPlayerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'avatar_url'  => new sfWidgetFormFilterInput(),
      'trade_price' => new sfWidgetFormFilterInput(),
      'in_team'     => new sfWidgetFormFilterInput(),
      'off_team'    => new sfWidgetFormFilterInput(),
      'link_url'    => new sfWidgetFormFilterInput(),
      'category'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'order_num'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'        => new sfValidatorPass(array('required' => false)),
      'avatar_url'  => new sfValidatorPass(array('required' => false)),
      'trade_price' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'in_team'     => new sfValidatorPass(array('required' => false)),
      'off_team'    => new sfValidatorPass(array('required' => false)),
      'link_url'    => new sfValidatorPass(array('required' => false)),
      'category'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_num'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('transfer_player_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'transferPlayer';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'name'        => 'Text',
      'avatar_url'  => 'Text',
      'trade_price' => 'Number',
      'in_team'     => 'Text',
      'off_team'    => 'Text',
      'link_url'    => 'Text',
      'category'    => 'Number',
      'type'        => 'Number',
      'order_num'   => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
