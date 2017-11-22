<?php

/**
 * TrdBusinessCreditlog filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdBusinessCreditlogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'      => new sfWidgetFormFilterInput(),
      'admin_id' => new sfWidgetFormFilterInput(),
      'type'     => new sfWidgetFormFilterInput(),
      'num'      => new sfWidgetFormFilterInput(),
      'note'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'admin_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'note'     => new sfValidatorPass(array('required' => false)),
      'date'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_business_creditlog_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdBusinessCreditlog';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'uid'      => 'Number',
      'admin_id' => 'Number',
      'type'     => 'Number',
      'num'      => 'Number',
      'note'     => 'Text',
      'date'     => 'Number',
    );
  }
}
