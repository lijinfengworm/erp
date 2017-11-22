<?php

/**
 * TrdGoodsTagRelation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsTagRelationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id' => new sfWidgetFormFilterInput(),
      'group_id' => new sfWidgetFormFilterInput(),
      'tag_id'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'goods_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tag_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_tag_relation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsTagRelation';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'goods_id' => 'Number',
      'group_id' => 'Number',
      'tag_id'   => 'Number',
    );
  }
}
