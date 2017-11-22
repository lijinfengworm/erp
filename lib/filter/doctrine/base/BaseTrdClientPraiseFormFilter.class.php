<?php

/**
 * TrdClientPraise filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdClientPraiseFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'client_id'    => new sfWidgetFormFilterInput(),
      'client_str'   => new sfWidgetFormFilterInput(),
      'client_token' => new sfWidgetFormFilterInput(),
      'wpclient_str' => new sfWidgetFormFilterInput(),
      'news_id'      => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'create_time'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_delete'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'client_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_str'   => new sfValidatorPass(array('required' => false)),
      'client_token' => new sfValidatorPass(array('required' => false)),
      'wpclient_str' => new sfValidatorPass(array('required' => false)),
      'news_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_delete'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_client_praise_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdClientPraise';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'client_id'    => 'Number',
      'client_str'   => 'Text',
      'client_token' => 'Text',
      'wpclient_str' => 'Text',
      'news_id'      => 'Number',
      'type'         => 'Number',
      'create_time'  => 'Date',
      'is_delete'    => 'Number',
    );
  }
}
