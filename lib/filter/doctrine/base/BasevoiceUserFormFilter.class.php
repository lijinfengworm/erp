<?php

/**
 * voiceUser filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasevoiceUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_intro' => new sfWidgetFormFilterInput(),
      'supports'   => new sfWidgetFormFilterInput(),
      'light'      => new sfWidgetFormFilterInput(),
      'attr'       => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_name'  => new sfValidatorPass(array('required' => false)),
      'user_intro' => new sfValidatorPass(array('required' => false)),
      'supports'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'light'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attr'       => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('voice_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'voiceUser';
  }

  public function getFields()
  {
    return array(
      'user_id'    => 'Number',
      'user_name'  => 'Text',
      'user_intro' => 'Text',
      'supports'   => 'Number',
      'light'      => 'Number',
      'attr'       => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
