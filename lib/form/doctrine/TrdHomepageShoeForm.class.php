<?php

/**
 * TrdHomepageShoe form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHomepageShoeForm extends BaseTrdHomepageShoeForm
{
    public function configure()
    {
        unset($this["created_at"]);
        unset($this["updated_at"]);

        //上传logo1
        $this->setWidget('logo1', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getLogo1(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('logo1',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => false,
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/trade/homepage/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));

        //上传logo2
        $this->setWidget('logo2', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getLogo2(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('logo2',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => false,
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/trade/homepage/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));
    }
    public function processValues($values) {
        if(isset($values['logo1']) && is_object($values['logo1'])){
            $imgpath1 = $values['logo1']->getPath();
        }

        if(isset($values['logo2']) && is_object($values['logo2'])){
            $imgpath1 = $values['logo2']->getPath();
        }

        $values = parent::processValues($values);

        if(isset($values['logo1']) && isset($imgpath1)){
            $img1 = strtolower($imgpath1 . $values['logo1']);

            $i = new Imagick($img1);
            //$i->thumbnailImage(400, 300);
            $i->writeImages($img1, true);
        }

        if(strstr($values["logo1"], "/") == false && $values["logo1"]) {
            $values["logo1"] = '/' . basename(sfConfig::get('sf_upload_dir')) . '/trade/homepage/' . $values["logo1"];
        }

        if(isset($values['logo2']) && isset($imgpath1)){
            $img2 = strtolower($imgpath1 . $values['logo2']);

            $i = new Imagick($img2);
            //$i->thumbnailImage(400, 300);
            $i->writeImages($img2, true);
        }

        if(strstr($values["logo2"], "/") == false && $values["logo2"]) {
            $values["logo2"] = '/' . basename(sfConfig::get('sf_upload_dir')) . '/trade/homepage/' . $values["logo2"];
        }


        return $values;
    }
}
