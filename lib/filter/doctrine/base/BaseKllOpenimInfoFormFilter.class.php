<?php

/**
 * KllOpenimInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllOpenimInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormFilterInput(),
      'client_str'    => new sfWidgetFormFilterInput(),
      'cookie_str'    => new sfWidgetFormFilterInput(),
      'open_username' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'open_password' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_str'    => new sfValidatorPass(array('required' => false)),
      'cookie_str'    => new sfValidatorPass(array('required' => false)),
      'open_username' => new sfValidatorPass(array('required' => false)),
      'open_password' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_openim_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllOpenimInfo';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'user_id'       => 'Number',
      'client_str'    => 'Text',
      'cookie_str'    => 'Text',
      'open_username' => 'Text',
      'open_password' => 'Text',
    );
  }
}
