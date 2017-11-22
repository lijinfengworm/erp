<?php

/**
 * TrdAmzProduct filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdAmzProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'shid'       => new sfWidgetFormFilterInput(),
      'amzid'      => new sfWidgetFormFilterInput(),
      'imgurl'     => new sfWidgetFormFilterInput(),
      'enname'     => new sfWidgetFormFilterInput(),
      'cnname'     => new sfWidgetFormFilterInput(),
      'category'   => new sfWidgetFormFilterInput(),
      'brand'      => new sfWidgetFormFilterInput(),
      'price'      => new sfWidgetFormFilterInput(),
      'comment'    => new sfWidgetFormFilterInput(),
      'hdtype'     => new sfWidgetFormFilterInput(),
      'oldhdtype'  => new sfWidgetFormFilterInput(),
      'shtype'     => new sfWidgetFormFilterInput(),
      'page'       => new sfWidgetFormFilterInput(),
      'status'     => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'shid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amzid'      => new sfValidatorPass(array('required' => false)),
      'imgurl'     => new sfValidatorPass(array('required' => false)),
      'enname'     => new sfValidatorPass(array('required' => false)),
      'cnname'     => new sfValidatorPass(array('required' => false)),
      'category'   => new sfValidatorPass(array('required' => false)),
      'brand'      => new sfValidatorPass(array('required' => false)),
      'price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'comment'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hdtype'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'oldhdtype'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shtype'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'page'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_amz_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdAmzProduct';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'shid'       => 'Number',
      'amzid'      => 'Text',
      'imgurl'     => 'Text',
      'enname'     => 'Text',
      'cnname'     => 'Text',
      'category'   => 'Text',
      'brand'      => 'Text',
      'price'      => 'Number',
      'comment'    => 'Number',
      'hdtype'     => 'Number',
      'oldhdtype'  => 'Number',
      'shtype'     => 'Number',
      'page'       => 'Number',
      'status'     => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
