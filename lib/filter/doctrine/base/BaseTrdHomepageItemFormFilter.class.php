<?php

/**
 * TrdHomepageItem filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdHomepageItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_all_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'           => new sfWidgetFormFilterInput(),
      'logo'            => new sfWidgetFormFilterInput(),
      'link'            => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'category_all_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Category'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'logo'            => new sfValidatorPass(array('required' => false)),
      'link'            => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_homepage_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdHomepageItem';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'category_all_id' => 'ForeignKey',
      'title'           => 'Text',
      'price'           => 'Number',
      'logo'            => 'Text',
      'link'            => 'Text',
      'status'          => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
