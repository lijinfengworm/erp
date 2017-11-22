<?php

/**
 * TrdCollections form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdCollectionsForm extends BaseTrdCollectionsForm
{
    public function configure()
    {
        unset($this["created_at"]);
        unset($this["updated_at"]);
        unset($this["other_contents"]);
        $this->setWidget('memo', new sfWidgetFormTextarea(array(), array('rows' => 7, 'cols' => 50)));

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
                'required' => $this->isNew(),
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/trade/collection/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));
        //上传logo
        $this->setWidget('pad_logo', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getPadLogo(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'with_delete' => false            
        )));

        $this->setValidator('pad_logo',
            new sfValidatorFile(array(
                'max_size' => 2000000,
                'required' => $this->isNew(),
                'mime_types' =>'web_images',
                'path' => sfConfig::get('sf_upload_dir') .'/trade/collection/'),
            array('required' => '图片必填', 'max_size' => '图片最大2M', 'mime_types' => '图片格式错误')
        ));
    }

    public function processValues($values) {
        if(isset($values['logo']) && is_object($values['logo'])){
            $imgpath = $values['logo']->getPath();
        }
        if(isset($values['pad_logo']) && is_object($values['pad_logo'])){
            $imgpath_pad = $values['pad_logo']->getPath();
        }

        $values = parent::processValues($values);

        if(isset($values['logo']) && isset($imgpath)){
            $img = strtolower($imgpath . $values['logo']);

            $i = new Imagick($img);
            $i->writeImages($img, true);
        }

        if(strstr($values["logo"], "/") == false) {
            $values["logo"] = '/uploads/trade/collection/' . $values["logo"];
        }
        if(isset($values['pad_logo']) && isset($imgpath_pad)){
            $img_pad = strtolower($imgpath_pad . $values['pad_logo']);

            $i = new Imagick($img_pad);
            $i->thumbnailImage(1860, 512);
            $i->writeImages($img_pad, true);
        }

        if(strstr($values["pad_logo"], "/") == false) {
            $values["pad_logo"] = '/uploads/trade/collection/' . $values["pad_logo"];
        }

        return $values;
    }
}
