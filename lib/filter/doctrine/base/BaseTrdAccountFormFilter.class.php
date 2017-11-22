<?php

/**
 * TrdAccount filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAccountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'integral'        => new sfWidgetFormFilterInput(),
      'gold'            => new sfWidgetFormFilterInput(),
      'shaiwu_integral' => new sfWidgetFormFilterInput(),
      'shaiwu_gold'     => new sfWidgetFormFilterInput(),
      'integral_total'  => new sfWidgetFormFilterInput(),
      'gold_total'      => new sfWidgetFormFilterInput(),
      'grant_uid'       => new sfWidgetFormFilterInput(),
      'grant_username'  => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'integral'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gold'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shaiwu_integral' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shaiwu_gold'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'integral_total'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gold_total'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_uid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grant_username'  => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAccount';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'integral'        => 'Number',
      'gold'            => 'Number',
      'shaiwu_integral' => 'Number',
      'shaiwu_gold'     => 'Number',
      'integral_total'  => 'Number',
      'gold_total'      => 'Number',
      'grant_uid'       => 'Number',
      'grant_username'  => 'Text',
      'status'          => 'Number',
    );
  }
}
