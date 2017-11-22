<?php

/**
 * wgMemberCard filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewgMemberCardFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wpgame_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpGame'), 'add_empty' => true)),
      'wpserver_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpServer'), 'add_empty' => true)),
      'wggonghuimember_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wgGonghuiMember'), 'add_empty' => true)),
      'card_no'            => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'wpgame_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpGame'), 'column' => 'id')),
      'wpserver_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpServer'), 'column' => 'id')),
      'wggonghuimember_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wgGonghuiMember'), 'column' => 'id')),
      'card_no'            => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('wg_member_card_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wgMemberCard';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'wpgame_id'          => 'ForeignKey',
      'wpserver_id'        => 'ForeignKey',
      'wggonghuimember_id' => 'ForeignKey',
      'card_no'            => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
