<?php

/**
 * TrdGoodsCategory filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsCategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'pid'    => new sfWidgetFormFilterInput(),
      'name'   => new sfWidgetFormFilterInput(),
      'status' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'pid'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'   => new sfValidatorPass(array('required' => false)),
      'status' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsCategory';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'pid'    => 'Number',
      'name'   => 'Text',
      'status' => 'Number',
    );
  }
}
