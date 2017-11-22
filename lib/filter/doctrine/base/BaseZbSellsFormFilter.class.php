<?php

/**
 * ZbSells filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZbSellsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZbProducts'), 'add_empty' => true)),
      'title'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'origin'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'freight_payer' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'give_money'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'product_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZbProducts'), 'column' => 'id')),
      'title'         => new sfValidatorPass(array('required' => false)),
      'origin'        => new sfValidatorPass(array('required' => false)),
      'url'           => new sfValidatorPass(array('required' => false)),
      'price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'freight_payer' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'give_money'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('zb_sells_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZbSells';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'product_id'    => 'ForeignKey',
      'title'         => 'Text',
      'origin'        => 'Text',
      'url'           => 'Text',
      'price'         => 'Number',
      'freight_payer' => 'Number',
      'give_money'    => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'deleted_at'    => 'Date',
    );
  }
}
