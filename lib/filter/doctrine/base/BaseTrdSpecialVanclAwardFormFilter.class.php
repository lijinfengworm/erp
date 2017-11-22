<?php

/**
 * TrdSpecialVanclAward filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdSpecialVanclAwardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(),
      'probability'     => new sfWidgetFormFilterInput(),
      'is_limited'      => new sfWidgetFormFilterInput(),
      'num'             => new sfWidgetFormFilterInput(),
      'message_title'   => new sfWidgetFormFilterInput(),
      'message_content' => new sfWidgetFormFilterInput(),
      'send_uid'        => new sfWidgetFormFilterInput(),
      'send_username'   => new sfWidgetFormFilterInput(),
      'kaluli'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'probability'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_limited'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'message_title'   => new sfValidatorPass(array('required' => false)),
      'message_content' => new sfValidatorPass(array('required' => false)),
      'send_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'send_username'   => new sfValidatorPass(array('required' => false)),
      'kaluli'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_special_vancl_award_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdSpecialVanclAward';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'probability'     => 'Number',
      'is_limited'      => 'Number',
      'num'             => 'Number',
      'message_title'   => 'Text',
      'message_content' => 'Text',
      'send_uid'        => 'Number',
      'send_username'   => 'Text',
      'kaluli'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'deleted_at'      => 'Date',
    );
  }
}
