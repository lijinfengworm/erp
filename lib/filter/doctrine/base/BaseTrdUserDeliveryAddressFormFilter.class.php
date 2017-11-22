<?php

/**
 * TrdUserDeliveryAddress filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdUserDeliveryAddressFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hupu_uid'        => new sfWidgetFormFilterInput(),
      'hupu_username'   => new sfWidgetFormFilterInput(),
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'postcode'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'province'        => new sfWidgetFormFilterInput(),
      'city'            => new sfWidgetFormFilterInput(),
      'area'            => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(),
      'phonesection'    => new sfWidgetFormFilterInput(),
      'phonecode'       => new sfWidgetFormFilterInput(),
      'phoneext'        => new sfWidgetFormFilterInput(),
      'region'          => new sfWidgetFormFilterInput(),
      'street'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'identity_number' => new sfWidgetFormFilterInput(),
      'defaultflag'     => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hupu_uid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hupu_username'   => new sfValidatorPass(array('required' => false)),
      'name'            => new sfValidatorPass(array('required' => false)),
      'postcode'        => new sfValidatorPass(array('required' => false)),
      'province'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'city'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'area'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'phonesection'    => new sfValidatorPass(array('required' => false)),
      'phonecode'       => new sfValidatorPass(array('required' => false)),
      'phoneext'        => new sfValidatorPass(array('required' => false)),
      'region'          => new sfValidatorPass(array('required' => false)),
      'street'          => new sfValidatorPass(array('required' => false)),
      'identity_number' => new sfValidatorPass(array('required' => false)),
      'defaultflag'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('trd_user_delivery_address_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdUserDeliveryAddress';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'hupu_uid'        => 'Number',
      'hupu_username'   => 'Text',
      'name'            => 'Text',
      'postcode'        => 'Text',
      'province'        => 'Number',
      'city'            => 'Number',
      'area'            => 'Number',
      'mobile'          => 'Text',
      'phonesection'    => 'Text',
      'phonecode'       => 'Text',
      'phoneext'        => 'Text',
      'region'          => 'Text',
      'street'          => 'Text',
      'identity_number' => 'Text',
      'defaultflag'     => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
