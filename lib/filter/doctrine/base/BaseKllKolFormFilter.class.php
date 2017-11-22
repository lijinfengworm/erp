<?php

/**
 * KllKol filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'     => new sfWidgetFormFilterInput(),
      'abstract'    => new sfWidgetFormFilterInput(),
      'home_page'   => new sfWidgetFormFilterInput(),
      'channel_id'  => new sfWidgetFormFilterInput(),
      'benefits_id' => new sfWidgetFormFilterInput(),
      'account'     => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'commision'   => new sfWidgetFormFilterInput(),
      'remark'      => new sfWidgetFormFilterInput(),
      'ct_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_name'   => new sfWidgetFormFilterInput(),
      'mobile'      => new sfWidgetFormFilterInput(),
      'nick_name'   => new sfWidgetFormFilterInput(),
      'head_image'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'abstract'    => new sfValidatorPass(array('required' => false)),
      'home_page'   => new sfValidatorPass(array('required' => false)),
      'channel_id'  => new sfValidatorPass(array('required' => false)),
      'benefits_id' => new sfValidatorPass(array('required' => false)),
      'account'     => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorPass(array('required' => false)),
      'commision'   => new sfValidatorPass(array('required' => false)),
      'remark'      => new sfValidatorPass(array('required' => false)),
      'ct_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_name'   => new sfValidatorPass(array('required' => false)),
      'mobile'      => new sfValidatorPass(array('required' => false)),
      'nick_name'   => new sfValidatorPass(array('required' => false)),
      'head_image'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKol';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'user_id'     => 'Number',
      'abstract'    => 'Text',
      'home_page'   => 'Text',
      'channel_id'  => 'Text',
      'benefits_id' => 'Text',
      'account'     => 'Text',
      'status'      => 'Text',
      'commision'   => 'Text',
      'remark'      => 'Text',
      'ct_time'     => 'Date',
      'user_name'   => 'Text',
      'mobile'      => 'Text',
      'nick_name'   => 'Text',
      'head_image'  => 'Text',
    );
  }
}
