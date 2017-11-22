<?php

/**
 * KllKolChannelSku filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolChannelSkuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'kid'     => new sfWidgetFormFilterInput(),
      'item_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'kid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'item_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_channel_sku_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolChannelSku';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'kid'     => 'Number',
      'item_id' => 'Number',
    );
  }
}
