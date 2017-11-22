<?php

/**
 * TrdCabbage form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdCabbageForm extends BaseTrdCabbageForm
{
  public function configure() {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['is_delete']);
        
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 50, 'min_length' => 1), array('required' => '标题必填',  'max_length' => '标题不大于25个字', 'min_length' => '标题不少于1个字')));
        $this->setWidget('intro', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 6)));
        $this->setValidator('intro', new sfValidatorString(array('required' => false, 'trim' => true,'min_length' => 0, 'max_length' => 500), array('max_length' => '简介不能大于500个字符')));

        $this->setWidget('link_url', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 200)));
        $this->setValidator('link_url', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '链接地址必填', 'invalid' => '链接地址格式错误')));

        $this->setWidget('img_path', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getImgPath(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImgPath(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('img_path', new sfValidatorFile(array(
            'validated_file_class' => 'dateValidatedFile', 
            'required' => false, 'max_size' => 1000000,
            'path' => sfConfig::get('sf_upload_dir') . '/trade/cabbage/' . date('ymd') . '/',
            'mime_types' => 'web_images'),array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大1M')));
    }

    public function processValues($values) {
        if (isset($values['img_path']) && is_object($values['img_path'])) {
            $haschange['img_path'] = 1;
        }
        
        
        $values = parent::processValues($values);
        //处理图片
        if (isset($values['img_path']) && isset($haschange['img_path'])) {
            $img = sfConfig::get('sf_upload_dir') . '/trade/cabbage/' . $values['img_path'];
            $i = new Imagick($img);
            $i->thumbnailImage(400, 400);
            $i->writeImages($img, true);
            $values['img_path'] =  '/uploads/trade/cabbage/' . $values['img_path'];
        }
        return $values;
    }

}
