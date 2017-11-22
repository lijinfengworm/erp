<?php

/**
 * KllSendMsg filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllSendMsgFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mobile'  => new sfWidgetFormFilterInput(),
      'opt_uid' => new sfWidgetFormFilterInput(),
      'nums'    => new sfWidgetFormFilterInput(),
      'stime'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'mobile'  => new sfValidatorPass(array('required' => false)),
      'opt_uid' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nums'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stime'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_send_msg_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllSendMsg';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'mobile'  => 'Text',
      'opt_uid' => 'Number',
      'nums'    => 'Number',
      'stime'   => 'Number',
    );
  }
}
