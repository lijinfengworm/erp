<?php

/**
 * TrdNoticesCount filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdNoticesCountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'   => new sfWidgetFormFilterInput(),
      'type1' => new sfWidgetFormFilterInput(),
      'type2' => new sfWidgetFormFilterInput(),
      'type3' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'uid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type1' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type2' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type3' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_notices_count_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdNoticesCount';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'uid'   => 'Number',
      'type1' => 'Number',
      'type2' => 'Number',
      'type3' => 'Number',
    );
  }
}
