<?php

/**
 * TrdTaobaoUrlJump filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdTaobaoUrlJumpFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'jump_url'         => new sfWidgetFormFilterInput(),
      'android_jump_url' => new sfWidgetFormFilterInput(),
      'ios_jump_url'     => new sfWidgetFormFilterInput(),
      'wp_jump_url'      => new sfWidgetFormFilterInput(),
      'addtime'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'item_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'jump_url'         => new sfValidatorPass(array('required' => false)),
      'android_jump_url' => new sfValidatorPass(array('required' => false)),
      'ios_jump_url'     => new sfValidatorPass(array('required' => false)),
      'wp_jump_url'      => new sfValidatorPass(array('required' => false)),
      'addtime'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_taobao_url_jump_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdTaobaoUrlJump';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'item_id'          => 'Number',
      'jump_url'         => 'Text',
      'android_jump_url' => 'Text',
      'ios_jump_url'     => 'Text',
      'wp_jump_url'      => 'Text',
      'addtime'          => 'Date',
    );
  }
}
