<?php

/**
 * wpTransferHistory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpTransferHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'wpserver_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wpserver'), 'add_empty' => true)),
      'wporder_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('wporder'), 'add_empty' => true)),
      'action'       => new sfWidgetFormFilterInput(),
      'result'       => new sfWidgetFormFilterInput(),
      'return_value' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'wpserver_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wpserver'), 'column' => 'id')),
      'wporder_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('wporder'), 'column' => 'id')),
      'action'       => new sfValidatorPass(array('required' => false)),
      'result'       => new sfValidatorPass(array('required' => false)),
      'return_value' => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('wp_transfer_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpTransferHistory';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'wpserver_id'  => 'ForeignKey',
      'wporder_id'   => 'ForeignKey',
      'action'       => 'Text',
      'result'       => 'Text',
      'return_value' => 'Text',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
