<?php

/**
 * KllAttachment filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllAttachmentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'aid'      => new sfWidgetFormFilterInput(),
      'type'     => new sfWidgetFormFilterInput(),
      'original' => new sfWidgetFormFilterInput(),
      'medium'   => new sfWidgetFormFilterInput(),
      'small'    => new sfWidgetFormFilterInput(),
      'is_use'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'aid'      => new sfValidatorPass(array('required' => false)),
      'type'     => new sfValidatorPass(array('required' => false)),
      'original' => new sfValidatorPass(array('required' => false)),
      'medium'   => new sfValidatorPass(array('required' => false)),
      'small'    => new sfValidatorPass(array('required' => false)),
      'is_use'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_attachment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllAttachment';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'aid'      => 'Text',
      'type'     => 'Text',
      'original' => 'Text',
      'medium'   => 'Text',
      'small'    => 'Text',
      'is_use'   => 'Text',
    );
  }
}
