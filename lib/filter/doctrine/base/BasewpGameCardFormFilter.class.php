<?php

/**
 * wpGameCard filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpGameCardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wpgame_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpGame'), 'add_empty' => true)),
      'wpserver_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpServers'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormFilterInput(),
      'username'     => new sfWidgetFormFilterInput(),
      'number'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'valid_period' => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'wpgame_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpGame'), 'column' => 'id')),
      'wpserver_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpServers'), 'column' => 'id')),
      'user_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'     => new sfValidatorPass(array('required' => false)),
      'number'       => new sfValidatorPass(array('required' => false)),
      'value'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'valid_period' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('wp_game_card_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpGameCard';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'wpgame_id'    => 'ForeignKey',
      'wpserver_id'  => 'ForeignKey',
      'user_id'      => 'Number',
      'username'     => 'Text',
      'number'       => 'Text',
      'value'        => 'Number',
      'start_time'   => 'Date',
      'valid_period' => 'Number',
      'status'       => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
