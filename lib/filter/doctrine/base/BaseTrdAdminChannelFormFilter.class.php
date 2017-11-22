<?php

/**
 * TrdAdminChannel filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAdminChannelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'identify'    => new sfWidgetFormFilterInput(),
      'channel'     => new sfWidgetFormFilterInput(),
      'manager'     => new sfWidgetFormFilterInput(),
      'create_time' => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'identify'    => new sfValidatorPass(array('required' => false)),
      'channel'     => new sfValidatorPass(array('required' => false)),
      'manager'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time' => new sfValidatorPass(array('required' => false)),
      'update_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_admin_channel_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAdminChannel';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'identify'    => 'Text',
      'channel'     => 'Text',
      'manager'     => 'Number',
      'create_time' => 'Text',
      'update_time' => 'Text',
    );
  }
}
