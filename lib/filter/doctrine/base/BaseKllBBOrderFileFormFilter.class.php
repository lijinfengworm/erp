<?php

/**
 * KllBBOrderFile filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllBBOrderFileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'source'      => new sfWidgetFormFilterInput(),
      'uid'         => new sfWidgetFormFilterInput(),
      'file'        => new sfWidgetFormFilterInput(),
      'number'      => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormFilterInput(),
      'surplus'     => new sfWidgetFormFilterInput(),
      'batch'       => new sfWidgetFormFilterInput(),
      'creat_time'  => new sfWidgetFormFilterInput(),
      'update_time' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'source'      => new sfValidatorPass(array('required' => false)),
      'uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'file'        => new sfValidatorPass(array('required' => false)),
      'number'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'      => new sfValidatorPass(array('required' => false)),
      'surplus'     => new sfValidatorPass(array('required' => false)),
      'batch'       => new sfValidatorPass(array('required' => false)),
      'creat_time'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'update_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_bb_order_file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllBBOrderFile';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Text',
      'source'      => 'Text',
      'uid'         => 'Number',
      'file'        => 'Text',
      'number'      => 'Number',
      'status'      => 'Text',
      'surplus'     => 'Text',
      'batch'       => 'Text',
      'creat_time'  => 'Number',
      'update_time' => 'Number',
    );
  }
}
