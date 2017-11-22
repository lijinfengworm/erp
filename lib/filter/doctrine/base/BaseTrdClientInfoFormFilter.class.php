<?php

/**
 * TrdClientInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdClientInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'      => new sfWidgetFormFilterInput(),
      'client_str'   => new sfWidgetFormFilterInput(),
      'client_token' => new sfWidgetFormFilterInput(),
      'wpclient_str' => new sfWidgetFormFilterInput(),
      'wp_url'       => new sfWidgetFormFilterInput(),
      'first_virst'  => new sfWidgetFormFilterInput(),
      'last_virst'   => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'ios_type'     => new sfWidgetFormFilterInput(),
      'push_switch'  => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_str'   => new sfValidatorPass(array('required' => false)),
      'client_token' => new sfValidatorPass(array('required' => false)),
      'wpclient_str' => new sfValidatorPass(array('required' => false)),
      'wp_url'       => new sfValidatorPass(array('required' => false)),
      'first_virst'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_virst'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ios_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'push_switch'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_client_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdClientInfo';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'user_id'      => 'Number',
      'client_str'   => 'Text',
      'client_token' => 'Text',
      'wpclient_str' => 'Text',
      'wp_url'       => 'Text',
      'first_virst'  => 'Number',
      'last_virst'   => 'Number',
      'type'         => 'Number',
      'ios_type'     => 'Number',
      'push_switch'  => 'Number',
      'status'       => 'Number',
    );
  }
}
