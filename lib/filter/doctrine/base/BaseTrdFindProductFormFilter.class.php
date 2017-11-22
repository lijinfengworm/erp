<?php

/**
 * TrdFindProduct filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdFindProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'memo'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'         => new sfWidgetFormFilterInput(),
      'tag'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'root_id'       => new sfWidgetFormFilterInput(),
      'children_id'   => new sfWidgetFormFilterInput(),
      'root_name'     => new sfWidgetFormFilterInput(),
      'children_name' => new sfWidgetFormFilterInput(),
      'attr_collect'  => new sfWidgetFormFilterInput(),
      'is_showsports' => new sfWidgetFormFilterInput(),
      'publish_date'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'         => new sfValidatorPass(array('required' => false)),
      'memo'          => new sfValidatorPass(array('required' => false)),
      'price'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tag'           => new sfValidatorPass(array('required' => false)),
      'root_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'children_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_name'     => new sfValidatorPass(array('required' => false)),
      'children_name' => new sfValidatorPass(array('required' => false)),
      'attr_collect'  => new sfValidatorPass(array('required' => false)),
      'is_showsports' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish_date'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_find_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdFindProduct';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'title'         => 'Text',
      'memo'          => 'Text',
      'price'         => 'Number',
      'tag'           => 'Text',
      'root_id'       => 'Number',
      'children_id'   => 'Number',
      'root_name'     => 'Text',
      'children_name' => 'Text',
      'attr_collect'  => 'Text',
      'is_showsports' => 'Number',
      'publish_date'  => 'Date',
    );
  }
}
