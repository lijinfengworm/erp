<?php

/**
 * TrdGoodsAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'  => new sfWidgetFormFilterInput(),
      'content'   => new sfWidgetFormFilterInput(),
      'pic_count' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'goods_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'   => new sfValidatorPass(array('required' => false)),
      'pic_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsAttr';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'goods_id'  => 'Number',
      'content'   => 'Text',
      'pic_count' => 'Number',
    );
  }
}
