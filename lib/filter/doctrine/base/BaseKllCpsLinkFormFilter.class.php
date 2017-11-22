<?php

/**
 * KllCpsLink filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllCpsLinkFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'          => new sfWidgetFormFilterInput(),
      'title'         => new sfWidgetFormFilterInput(),
      'channel'       => new sfWidgetFormFilterInput(),
      'link'          => new sfWidgetFormFilterInput(),
      'uid'           => new sfWidgetFormFilterInput(),
      'cps_user_id'   => new sfWidgetFormFilterInput(),
      'cps_user_name' => new sfWidgetFormFilterInput(),
      'item_id'       => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'code'          => new sfValidatorPass(array('required' => false)),
      'title'         => new sfValidatorPass(array('required' => false)),
      'channel'       => new sfValidatorPass(array('required' => false)),
      'link'          => new sfValidatorPass(array('required' => false)),
      'uid'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cps_user_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cps_user_name' => new sfValidatorPass(array('required' => false)),
      'item_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kll_cps_link_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllCpsLink';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'code'          => 'Text',
      'title'         => 'Text',
      'channel'       => 'Text',
      'link'          => 'Text',
      'uid'           => 'Number',
      'cps_user_id'   => 'Number',
      'cps_user_name' => 'Text',
      'item_id'       => 'Number',
      'status'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
