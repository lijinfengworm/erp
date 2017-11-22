<?php

/**
 * KllOrderFile form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllOrderFileForm extends BaseKllOrderFileForm
{
  public function configure()
  {
      $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          //    'height'=>400,
          //    'width'=>400,
          'path'=>'uploads/kaluli/train',
          //   'ratio'=>'1x1'
      );
      $this->setWidget('upload_path',new tradeWidgetFormFupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }
}
