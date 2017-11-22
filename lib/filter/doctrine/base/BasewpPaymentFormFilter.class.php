<?php

/**
 * wpPayment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasewpPaymentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'partner'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'alipay_key'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'seller_email' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'         => new sfValidatorPass(array('required' => false)),
      'partner'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alipay_key'   => new sfValidatorPass(array('required' => false)),
      'seller_email' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wp_payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'wpPayment';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'name'         => 'Text',
      'partner'      => 'Number',
      'alipay_key'   => 'Text',
      'seller_email' => 'Text',
    );
  }
}
