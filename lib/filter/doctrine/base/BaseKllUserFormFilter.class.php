<?php

/**
 * KllUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_name'       => new sfWidgetFormFilterInput(),
      'password'        => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(),
      'source'          => new sfWidgetFormFilterInput(),
      'ct_time'         => new sfWidgetFormFilterInput(),
      'up_time'         => new sfWidgetFormFilterInput(),
      'last_login_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_name'       => new sfValidatorPass(array('required' => false)),
      'password'        => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'source'          => new sfValidatorPass(array('required' => false)),
      'ct_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'up_time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_login_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllUser';
  }

  public function getFields()
  {
    return array(
      'user_id'         => 'Number',
      'user_name'       => 'Text',
      'password'        => 'Text',
      'mobile'          => 'Number',
      'source'          => 'Text',
      'ct_time'         => 'Number',
      'up_time'         => 'Number',
      'last_login_time' => 'Number',
    );
  }
}
