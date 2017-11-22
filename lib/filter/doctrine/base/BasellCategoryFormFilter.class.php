<?php

/**
 * llCategory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasellCategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_id'              => new sfWidgetFormFilterInput(),
      'name'                 => new sfWidgetFormFilterInput(),
      'pic'                  => new sfWidgetFormFilterInput(),
      'special_column_limit' => new sfWidgetFormFilterInput(),
      'special_column_order' => new sfWidgetFormFilterInput(),
      'lft'                  => new sfWidgetFormFilterInput(),
      'rgt'                  => new sfWidgetFormFilterInput(),
      'level'                => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'root_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'pic'                  => new sfValidatorPass(array('required' => false)),
      'special_column_limit' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'special_column_order' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ll_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'llCategory';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'root_id'              => 'Number',
      'name'                 => 'Text',
      'pic'                  => 'Text',
      'special_column_limit' => 'Number',
      'special_column_order' => 'Number',
      'lft'                  => 'Number',
      'rgt'                  => 'Number',
      'level'                => 'Number',
    );
  }
}
