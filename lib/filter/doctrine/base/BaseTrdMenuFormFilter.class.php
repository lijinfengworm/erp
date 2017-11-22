<?php

/**
 * TrdMenu filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdMenuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'root_id' => new sfWidgetFormFilterInput(),
      'name'    => new sfWidgetFormFilterInput(),
      'type'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'pic_url' => new sfWidgetFormFilterInput(),
      'sort'    => new sfWidgetFormFilterInput(),
      'lft'     => new sfWidgetFormFilterInput(),
      'rgt'     => new sfWidgetFormFilterInput(),
      'level'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'root_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'    => new sfValidatorPass(array('required' => false)),
      'type'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'pic_url' => new sfValidatorPass(array('required' => false)),
      'sort'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_menu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdMenu';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'root_id' => 'Number',
      'name'    => 'Text',
      'type'    => 'Boolean',
      'pic_url' => 'Text',
      'sort'    => 'Number',
      'lft'     => 'Number',
      'rgt'     => 'Number',
      'level'   => 'Number',
    );
  }
}
