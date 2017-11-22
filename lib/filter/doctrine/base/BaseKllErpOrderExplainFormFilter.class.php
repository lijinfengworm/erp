<?php

/**
 * KllErpOrderExplain filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllErpOrderExplainFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_number' => new sfWidgetFormFilterInput(),
      'remark'       => new sfWidgetFormFilterInput(),
      'pic'          => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(),
      'user'         => new sfWidgetFormFilterInput(),
      'create_time'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_number' => new sfValidatorPass(array('required' => false)),
      'remark'       => new sfValidatorPass(array('required' => false)),
      'pic'          => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorPass(array('required' => false)),
      'user'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'create_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_erp_order_explain_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllErpOrderExplain';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Text',
      'order_number' => 'Text',
      'remark'       => 'Text',
      'pic'          => 'Text',
      'type'         => 'Text',
      'user'         => 'Number',
      'create_time'  => 'Number',
    );
  }
}
