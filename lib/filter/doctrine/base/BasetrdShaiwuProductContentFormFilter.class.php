<?php

/**
 * trdShaiwuProductContent filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasetrdShaiwuProductContentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('trdShaiwuProduct'), 'add_empty' => true)),
      'content'    => new sfWidgetFormFilterInput(),
      'urls'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'product_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('trdShaiwuProduct'), 'column' => 'id')),
      'content'    => new sfValidatorPass(array('required' => false)),
      'urls'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('trd_shaiwu_product_content_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'trdShaiwuProductContent';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'product_id' => 'ForeignKey',
      'content'    => 'Text',
      'urls'       => 'Text',
    );
  }
}
