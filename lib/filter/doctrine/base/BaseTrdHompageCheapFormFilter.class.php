<?php

/**
 * TrdHompageCheap filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHompageCheapFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo'       => new sfWidgetFormFilterInput(),
      'link'       => new sfWidgetFormFilterInput(),
      'old_price'  => new sfWidgetFormFilterInput(),
      'new_price'  => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'      => new sfValidatorPass(array('required' => false)),
      'logo'       => new sfValidatorPass(array('required' => false)),
      'link'       => new sfValidatorPass(array('required' => false)),
      'old_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'new_price'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_hompage_cheap_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHompageCheap';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'title'      => 'Text',
      'logo'       => 'Text',
      'link'       => 'Text',
      'old_price'  => 'Number',
      'new_price'  => 'Number',
      'status'     => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
