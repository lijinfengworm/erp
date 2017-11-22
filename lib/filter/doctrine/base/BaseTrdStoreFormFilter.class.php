<?php

/**
 * TrdStore filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdStoreFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sort'       => new sfWidgetFormFilterInput(),
      'type'       => new sfWidgetFormFilterInput(),
      'is_index'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_haitao'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_display' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_delete'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'sort'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_index'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_haitao'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_display' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_delete'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('trd_store_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdStore';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'sort'       => 'Number',
      'type'       => 'Number',
      'is_index'   => 'Boolean',
      'is_haitao'  => 'Boolean',
      'is_display' => 'Boolean',
      'is_delete'  => 'Boolean',
    );
  }
}
