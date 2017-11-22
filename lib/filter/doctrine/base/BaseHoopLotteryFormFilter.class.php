<?php

/**
 * HoopLottery filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseHoopLotteryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'  => new sfWidgetFormFilterInput(),
      'match_num' => new sfWidgetFormFilterInput(),
      'sf'        => new sfWidgetFormFilterInput(),
      'rfsf'      => new sfWidgetFormFilterInput(),
      'dxf'       => new sfWidgetFormFilterInput(),
      'status'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'match_num' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sf'        => new sfValidatorPass(array('required' => false)),
      'rfsf'      => new sfValidatorPass(array('required' => false)),
      'dxf'       => new sfValidatorPass(array('required' => false)),
      'status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('hoop_lottery_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'HoopLottery';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'match_id'  => 'Number',
      'match_num' => 'Number',
      'sf'        => 'Text',
      'rfsf'      => 'Text',
      'dxf'       => 'Text',
      'status'    => 'Number',
    );
  }
}
