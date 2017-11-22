<?php

/**
 * TrdBusinessman filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdBusinessmanFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'       => new sfWidgetFormFilterInput(),
      'hupu_uid'       => new sfWidgetFormFilterInput(),
      'hupu_username'  => new sfWidgetFormFilterInput(),
      'phone'          => new sfWidgetFormFilterInput(),
      'email'          => new sfWidgetFormFilterInput(),
      'qq'             => new sfWidgetFormFilterInput(),
      'shop_url'       => new sfWidgetFormFilterInput(),
      'shop_name'      => new sfWidgetFormFilterInput(),
      'wanwan'         => new sfWidgetFormFilterInput(),
      'alliance'       => new sfWidgetFormFilterInput(),
      'alliance_trdno' => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'username'       => new sfValidatorPass(array('required' => false)),
      'hupu_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'  => new sfValidatorPass(array('required' => false)),
      'phone'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'email'          => new sfValidatorPass(array('required' => false)),
      'qq'             => new sfValidatorPass(array('required' => false)),
      'shop_url'       => new sfValidatorPass(array('required' => false)),
      'shop_name'      => new sfValidatorPass(array('required' => false)),
      'wanwan'         => new sfValidatorPass(array('required' => false)),
      'alliance'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alliance_trdno' => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_businessman_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBusinessman';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'username'       => 'Text',
      'hupu_uid'       => 'Number',
      'hupu_username'  => 'Text',
      'phone'          => 'Number',
      'email'          => 'Text',
      'qq'             => 'Text',
      'shop_url'       => 'Text',
      'shop_name'      => 'Text',
      'wanwan'         => 'Text',
      'alliance'       => 'Number',
      'alliance_trdno' => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
