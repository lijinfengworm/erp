<?php

/**
 * KllHupuApicontent filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllHupuApicontentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kll_hupu_title'    => new sfWidgetFormFilterInput(),
      'kll_hupu_subtitle' => new sfWidgetFormFilterInput(),
      'kll_hupu_imgpath'  => new sfWidgetFormFilterInput(),
      'kll_hupu_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'kll_hupu_url'      => new sfWidgetFormFilterInput(),
      'kll_hupu_type'     => new sfWidgetFormFilterInput(),
      'kll_hupu_status'   => new sfWidgetFormFilterInput(),
      'kll_hupu_origin'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'kll_hupu_title'    => new sfValidatorPass(array('required' => false)),
      'kll_hupu_subtitle' => new sfValidatorPass(array('required' => false)),
      'kll_hupu_imgpath'  => new sfValidatorPass(array('required' => false)),
      'kll_hupu_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'kll_hupu_url'      => new sfValidatorPass(array('required' => false)),
      'kll_hupu_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'kll_hupu_status'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'kll_hupu_origin'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_hupu_apicontent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllHupuApicontent';
  }

  public function getFields()
  {
    return array(
      'kll_hupu_id'       => 'Number',
      'kll_hupu_title'    => 'Text',
      'kll_hupu_subtitle' => 'Text',
      'kll_hupu_imgpath'  => 'Text',
      'kll_hupu_time'     => 'Date',
      'kll_hupu_url'      => 'Text',
      'kll_hupu_type'     => 'Number',
      'kll_hupu_status'   => 'Number',
      'kll_hupu_origin'   => 'Number',
    );
  }
}
