<?php

/**
 * GameOrderQueryLog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGameOrderQueryLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'request_url'  => new sfWidgetFormFilterInput(),
      'ip'           => new sfWidgetFormFilterInput(),
      'order_number' => new sfWidgetFormFilterInput(),
      'error_code'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'request_url'  => new sfValidatorPass(array('required' => false)),
      'ip'           => new sfValidatorPass(array('required' => false)),
      'order_number' => new sfValidatorPass(array('required' => false)),
      'error_code'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('game_order_query_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GameOrderQueryLog';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'request_url'  => 'Text',
      'ip'           => 'Text',
      'order_number' => 'Text',
      'error_code'   => 'Text',
    );
  }
}
