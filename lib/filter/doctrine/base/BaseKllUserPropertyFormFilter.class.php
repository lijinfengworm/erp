<?php

/**
 * KllUserProperty filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllUserPropertyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_name'       => new sfWidgetFormFilterInput(),
      'mail'            => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'sex'             => new sfWidgetFormFilterInput(),
      'province'        => new sfWidgetFormFilterInput(),
      'city'            => new sfWidgetFormFilterInput(),
      'profession'      => new sfWidgetFormFilterInput(),
      'info'            => new sfWidgetFormFilterInput(),
      'register_time'   => new sfWidgetFormFilterInput(),
      'last_login_time' => new sfWidgetFormFilterInput(),
      'pwd_level'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_name'       => new sfValidatorPass(array('required' => false)),
      'mail'            => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorPass(array('required' => false)),
      'sex'             => new sfValidatorPass(array('required' => false)),
      'province'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'city'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profession'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'info'            => new sfValidatorPass(array('required' => false)),
      'register_time'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_login_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pwd_level'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_user_property_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUserProperty';
  }

  public function getFields()
  {
    return array(
      'user_id'         => 'Number',
      'user_name'       => 'Text',
      'mail'            => 'Text',
      'status'          => 'Text',
      'sex'             => 'Text',
      'province'        => 'Number',
      'city'            => 'Number',
      'profession'      => 'Number',
      'info'            => 'Text',
      'register_time'   => 'Number',
      'last_login_time' => 'Number',
      'pwd_level'       => 'Number',
    );
  }
}
