<?php

/**
 * TrdHomepageCollection form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHomepageCollectionForm extends BaseTrdHomepageCollectionForm
{
    public function configure()
    {
        unset($this["created_at"]);
        unset($this["updated_at"]);

        //上传logo
        $this->setWidget('logo', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getLogo(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('logo',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => false,
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/trade/homepage/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));
    }

    public function processValues($values) {
        if(isset($values['logo']) && is_object($values['logo'])){
            $imgpath = $values['logo']->getPath();
        }

        $values = parent::processValues($values);

        if(isset($values['logo']) && isset($imgpath)){
            $img = strtolower($imgpath . $values['logo']);

            $i = new Imagick($img);
            $i->thumbnailImage(268, 182);
            $i->writeImages($img, true);
        }

        if(strstr($values["logo"], "/") == false && $values["logo"]) {
            $values["logo"] = '/' . basename(sfConfig::get('sf_upload_dir')) . '/trade/homepage/' . $values["logo"];
        }

        return $values;
    }
}
