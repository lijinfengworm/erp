<?php

/**
 * TrdNotices filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdNoticesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'       => new sfWidgetFormFilterInput(),
      'uid'        => new sfWidgetFormFilterInput(),
      'sender_uid' => new sfWidgetFormFilterInput(),
      'time'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sender_uid' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_notices_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNotices';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'type'       => 'Number',
      'uid'        => 'Number',
      'sender_uid' => 'Number',
      'time'       => 'Number',
    );
  }
}
