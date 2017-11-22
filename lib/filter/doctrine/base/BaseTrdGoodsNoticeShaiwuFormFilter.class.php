<?php

/**
 * TrdGoodsNoticeShaiwu filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdGoodsNoticeShaiwuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'shaiwu_id' => new sfWidgetFormFilterInput(),
      'time'      => new sfWidgetFormFilterInput(),
      'attrs'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'shaiwu_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'attrs'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_goods_notice_shaiwu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdGoodsNoticeShaiwu';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'shaiwu_id' => 'Number',
      'time'      => 'Number',
      'attrs'     => 'Text',
    );
  }
}
