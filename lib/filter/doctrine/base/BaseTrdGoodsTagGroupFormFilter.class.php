<?php

/**
 * TrdGoodsTagGroup filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsTagGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'click_type' => new sfWidgetFormFilterInput(),
      'name'       => new sfWidgetFormFilterInput(),
      'value'      => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'click_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'       => new sfValidatorPass(array('required' => false)),
      'value'      => new sfValidatorPass(array('required' => false)),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_tag_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsTagGroup';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'click_type' => 'Number',
      'name'       => 'Text',
      'value'      => 'Text',
      'status'     => 'Number',
    );
  }
}
