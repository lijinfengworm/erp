<?php

/**
 * KllKolChannel filter form base class.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKllKolChannelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'        => new sfWidgetFormFilterInput(),
      'abstract'     => new sfWidgetFormFilterInput(),
      'channel_code' => new sfWidgetFormFilterInput(),
      'times'        => new sfWidgetFormFilterInput(),
      'range'        => new sfWidgetFormFilterInput(),
      'discount'     => new sfWidgetFormFilterInput(),
      'toplimit'     => new sfWidgetFormFilterInput(),
      'start_time'   => new sfWidgetFormFilterInput(),
      'end_time'     => new sfWidgetFormFilterInput(),
      'commision'    => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'title'        => new sfValidatorPass(array('required' => false)),
      'abstract'     => new sfValidatorPass(array('required' => false)),
      'channel_code' => new sfValidatorPass(array('required' => false)),
      'times'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'range'        => new sfValidatorPass(array('required' => false)),
      'discount'     => new sfValidatorPass(array('required' => false)),
      'toplimit'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'start_time'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_time'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commision'    => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kll_kol_channel_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KllKolChannel';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'title'        => 'Text',
      'abstract'     => 'Text',
      'channel_code' => 'Text',
      'times'        => 'Number',
      'range'        => 'Text',
      'discount'     => 'Text',
      'toplimit'     => 'Number',
      'start_time'   => 'Number',
      'end_time'     => 'Number',
      'commision'    => 'Text',
      'status'       => 'Text',
    );
  }
}
