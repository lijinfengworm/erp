<?php

/**
 * columnMsgsDisplay filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasecolumnMsgsDisplayFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'head_pic'     => new sfWidgetFormFilterInput(),
      'type'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_headline'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'publish_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'view_profile' => new sfWidgetFormFilterInput(),
      'show_order'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'head_pic'     => new sfValidatorPass(array('required' => false)),
      'type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_headline'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'publish_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'view_profile' => new sfValidatorPass(array('required' => false)),
      'show_order'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('column_msgs_display_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'columnMsgsDisplay';
  }

  public function getFields()
  {
    return array(
      'cl_msg_id'    => 'Number',
      'head_pic'     => 'Text',
      'type'         => 'Number',
      'is_headline'  => 'Boolean',
      'publish_date' => 'Date',
      'view_profile' => 'Text',
      'show_order'   => 'Number',
    );
  }
}
