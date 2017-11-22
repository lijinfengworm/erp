<?php

/**
 * TrdShopInfo filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTrdShopInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'owner_name'       => new sfWidgetFormFilterInput(),
      'shop_category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TrdShopCategory'), 'add_empty' => true)),
      'shop_user_id'     => new sfWidgetFormFilterInput(),
      'shop_info'        => new sfWidgetFormFilterInput(),
      'memo'             => new sfWidgetFormFilterInput(),
      'logo'             => new sfWidgetFormFilterInput(),
      'link'             => new sfWidgetFormFilterInput(),
      'business'         => new sfWidgetFormFilterInput(),
      'location'         => new sfWidgetFormFilterInput(),
      'level'            => new sfWidgetFormFilterInput(),
      'good'             => new sfWidgetFormFilterInput(),
      'hupu_uid'         => new sfWidgetFormFilterInput(),
      'discount'         => new sfWidgetFormFilterInput(),
      'charge'           => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'position'         => new sfWidgetFormFilterInput(),
      'verify_status'    => new sfWidgetFormFilterInput(),
      'collect_count'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'owner_name'       => new sfValidatorPass(array('required' => false)),
      'shop_category_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TrdShopCategory'), 'column' => 'id')),
      'shop_user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shop_info'        => new sfValidatorPass(array('required' => false)),
      'memo'             => new sfValidatorPass(array('required' => false)),
      'logo'             => new sfValidatorPass(array('required' => false)),
      'link'             => new sfValidatorPass(array('required' => false)),
      'business'         => new sfValidatorPass(array('required' => false)),
      'location'         => new sfValidatorPass(array('required' => false)),
      'level'            => new sfValidatorPass(array('required' => false)),
      'good'             => new sfValidatorPass(array('required' => false)),
      'hupu_uid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'         => new sfValidatorPass(array('required' => false)),
      'charge'           => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'verify_status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'collect_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('trd_shop_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TrdShopInfo';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'owner_name'       => 'Text',
      'shop_category_id' => 'ForeignKey',
      'shop_user_id'     => 'Number',
      'shop_info'        => 'Text',
      'memo'             => 'Text',
      'logo'             => 'Text',
      'link'             => 'Text',
      'business'         => 'Text',
      'location'         => 'Text',
      'level'            => 'Text',
      'good'             => 'Text',
      'hupu_uid'         => 'Number',
      'discount'         => 'Text',
      'charge'           => 'Text',
      'status'           => 'Number',
      'position'         => 'Number',
      'verify_status'    => 'Number',
      'collect_count'    => 'Number',
    );
  }
}
