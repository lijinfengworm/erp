<?php

/**
 * KaluliItemAttr filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKaluliItemAttrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id'            => new sfWidgetFormFilterInput(),
      'content'            => new sfWidgetFormFilterInput(),
      'pic_detail'         => new sfWidgetFormFilterInput(),
      'comment_imgs_count' => new sfWidgetFormFilterInput(),
      'comment_count'      => new sfWidgetFormFilterInput(),
      'comment_tags_count' => new sfWidgetFormFilterInput(),
      'attrs'              => new sfWidgetFormFilterInput(),
      'review'             => new sfWidgetFormFilterInput(),
      'sales_count'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'item_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'            => new sfValidatorPass(array('required' => false)),
      'pic_detail'         => new sfValidatorPass(array('required' => false)),
      'comment_imgs_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_tags_count' => new sfValidatorPass(array('required' => false)),
      'attrs'              => new sfValidatorPass(array('required' => false)),
      'review'             => new sfValidatorPass(array('required' => false)),
      'sales_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('kaluli_item_attr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KaluliItemAttr';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'item_id'            => 'Number',
      'content'            => 'Text',
      'pic_detail'         => 'Text',
      'comment_imgs_count' => 'Number',
      'comment_count'      => 'Number',
      'comment_tags_count' => 'Text',
      'attrs'              => 'Text',
      'review'             => 'Text',
      'sales_count'        => 'Number',
    );
  }
}
