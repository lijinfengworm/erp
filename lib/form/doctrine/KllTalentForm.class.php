<?php

/**
 * KllTalent form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllTalentForm extends BaseKllTalentForm
{
  public function configure()
  {
    $rule = array(
        'required'=>true,
        'max_size'=>'500000',
        'path'=>'uploads/kaluli/attachment',
    );
    $this->setWidget('job', new sfWidgetFormInput([], []));
    $this->setWidget('interest', new sfWidgetFormInput([], []));
    $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
    $this->setWidget('att_id', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
  }
}
