<?php

/**
 * TrdGoodsTagGroupRelation filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsTagGroupRelationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'group_id'    => new sfWidgetFormFilterInput(),
      'category_id' => new sfWidgetFormFilterInput(),
      'value'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'group_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'value'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_tag_group_relation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsTagGroupRelation';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'group_id'    => 'Number',
      'category_id' => 'Number',
      'value'       => 'Text',
    );
  }
}
