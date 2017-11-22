<?php

/**
 * TrdCollections filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdCollectionsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name_color'     => new sfWidgetFormFilterInput(),
      'memo'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'memo_color'     => new sfWidgetFormFilterInput(),
      'logo'           => new sfWidgetFormFilterInput(),
      'logo_url'       => new sfWidgetFormFilterInput(),
      'pad_logo'       => new sfWidgetFormFilterInput(),
      'pad_logo_url'   => new sfWidgetFormFilterInput(),
      'shortcut'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_hide'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'other_contents' => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'name_color'     => new sfValidatorPass(array('required' => false)),
      'memo'           => new sfValidatorPass(array('required' => false)),
      'memo_color'     => new sfValidatorPass(array('required' => false)),
      'logo'           => new sfValidatorPass(array('required' => false)),
      'logo_url'       => new sfValidatorPass(array('required' => false)),
      'pad_logo'       => new sfValidatorPass(array('required' => false)),
      'pad_logo_url'   => new sfValidatorPass(array('required' => false)),
      'shortcut'       => new sfValidatorPass(array('required' => false)),
      'is_hide'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'other_contents' => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_collections_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdCollections';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'name'           => 'Text',
      'name_color'     => 'Text',
      'memo'           => 'Text',
      'memo_color'     => 'Text',
      'logo'           => 'Text',
      'logo_url'       => 'Text',
      'pad_logo'       => 'Text',
      'pad_logo_url'   => 'Text',
      'shortcut'       => 'Text',
      'is_hide'        => 'Boolean',
      'other_contents' => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
