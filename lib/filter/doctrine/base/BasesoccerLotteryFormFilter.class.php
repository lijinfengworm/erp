<?php

/**
 * soccerLottery filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesoccerLotteryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_num' => new sfWidgetFormFilterInput(),
      'intro'     => new sfWidgetFormFilterInput(),
      'status'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'home_logo' => new sfWidgetFormFilterInput(),
      'home_url'  => new sfWidgetFormFilterInput(),
      'away_logo' => new sfWidgetFormFilterInput(),
      'away_url'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'match_num' => new sfValidatorPass(array('required' => false)),
      'intro'     => new sfValidatorPass(array('required' => false)),
      'status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'home_logo' => new sfValidatorPass(array('required' => false)),
      'home_url'  => new sfValidatorPass(array('required' => false)),
      'away_logo' => new sfValidatorPass(array('required' => false)),
      'away_url'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soccer_lottery_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'soccerLottery';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'match_num' => 'Text',
      'intro'     => 'Text',
      'status'    => 'Number',
      'home_logo' => 'Text',
      'home_url'  => 'Text',
      'away_logo' => 'Text',
      'away_url'  => 'Text',
    );
  }
}
