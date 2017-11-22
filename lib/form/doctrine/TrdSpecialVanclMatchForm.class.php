<?php

/**
 * TrdSpecialVanclMatch form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdSpecialVanclMatchForm extends BaseTrdSpecialVanclMatchForm
{
  public function configure()
  {
    unset($this["created_at"]);
    unset($this["updated_at"]);
    unset($this["deleted_at"]);      

        //上传logo
        $this->setWidget('away_logo', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/' . basename(sfConfig::get('sf_upload_dir')).'/shihuo/special/vancal/match/' .$this->getObject()->getAwayLogo(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('away_logo',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => $this->isNew(),
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/shihuo/special/vancal/match/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        )); 
        
        $this->setWidget('home_logo', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/' . basename(sfConfig::get('sf_upload_dir')) .'/shihuo/special/vancal/match/'.$this->getObject()->getHomeLogo(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('home_logo',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => $this->isNew(),
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/shihuo/special/vancal/match/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));        
  }

}
