<?php

/**
 * KllBanner form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllBannerForm extends BaseKllBannerForm
{
  public function configure()
  {
      $this->setWidget('abstract',new sfWidgetFormTextarea());
//      $this->setWidget('abstract',new tradeWidgetFormUeditor());
      
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            'path'=>'uploads/kaluli/attachment',
        );
      $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
      $this->setWidget('att_id', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
      ////验证规则
//      $this->setValidator('att_id', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '附件必填')));
//      $this->setValidator('abstract', new sfValidatorString(array('required'=>true,'trim'=>true,'max_length'=>100),array('required'=>'banner简介必填','max_length'=>'简介长度小于100')));
//      
      $this->widgetSchema->setHelps(array(
      ));        
  }
}
