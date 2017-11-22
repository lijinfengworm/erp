<?php

/**
 * TrdGoodsStyle filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsStyleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'goods_id'   => new sfWidgetFormFilterInput(),
      'name'       => new sfWidgetFormFilterInput(),
      'pic'        => new sfWidgetFormFilterInput(),
      'value'      => new sfWidgetFormFilterInput(),
      'is_default' => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
      'hits'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'goods_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'       => new sfValidatorPass(array('required' => false)),
      'pic'        => new sfValidatorPass(array('required' => false)),
      'value'      => new sfValidatorPass(array('required' => false)),
      'is_default' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hits'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_style_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsStyle';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'goods_id'   => 'Number',
      'name'       => 'Text',
      'pic'        => 'Text',
      'value'      => 'Text',
      'is_default' => 'Number',
      'status'     => 'Number',
      'hits'       => 'Number',
    );
  }
}
