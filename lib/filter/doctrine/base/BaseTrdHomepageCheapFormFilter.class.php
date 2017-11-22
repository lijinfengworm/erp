<?php

/**
 * TrdHomepageCheap filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHomepageCheapFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo'            => new sfWidgetFormFilterInput(),
      'link'            => new sfWidgetFormFilterInput(),
      'old_price'       => new sfWidgetFormFilterInput(),
      'new_price'       => new sfWidgetFormFilterInput(),
      'is_default'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'category_all_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Category'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'logo'            => new sfValidatorPass(array('required' => false)),
      'link'            => new sfValidatorPass(array('required' => false)),
      'old_price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'new_price'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'is_default'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_homepage_cheap_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHomepageCheap';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'category_all_id' => 'ForeignKey',
      'title'           => 'Text',
      'logo'            => 'Text',
      'link'            => 'Text',
      'old_price'       => 'Number',
      'new_price'       => 'Number',
      'is_default'      => 'Boolean',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
