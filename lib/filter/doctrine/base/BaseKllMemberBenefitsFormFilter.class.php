<?php

/**
 * KllMemberBenefits filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllMemberBenefitsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'       => new sfWidgetFormFilterInput(),
      'title'      => new sfWidgetFormFilterInput(),
      'abstract'   => new sfWidgetFormFilterInput(),
      'link'       => new sfWidgetFormFilterInput(),
      'times'      => new sfWidgetFormFilterInput(),
      'type'       => new sfWidgetFormFilterInput(),
      'range'      => new sfWidgetFormFilterInput(),
      'discount'   => new sfWidgetFormFilterInput(),
      'toplimit'   => new sfWidgetFormFilterInput(),
      'start_time' => new sfWidgetFormFilterInput(),
      'end_time'   => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'code'       => new sfValidatorPass(array('required' => false)),
      'title'      => new sfValidatorPass(array('required' => false)),
      'abstract'   => new sfValidatorPass(array('required' => false)),
      'link'       => new sfValidatorPass(array('required' => false)),
      'times'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'       => new sfValidatorPass(array('required' => false)),
      'range'      => new sfValidatorPass(array('required' => false)),
      'discount'   => new sfValidatorPass(array('required' => false)),
      'toplimit'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'start_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_time'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_member_benefits_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllMemberBenefits';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'code'       => 'Text',
      'title'      => 'Text',
      'abstract'   => 'Text',
      'link'       => 'Text',
      'times'      => 'Number',
      'type'       => 'Text',
      'range'      => 'Text',
      'discount'   => 'Text',
      'toplimit'   => 'Number',
      'start_time' => 'Number',
      'end_time'   => 'Number',
      'status'     => 'Text',
    );
  }
}
