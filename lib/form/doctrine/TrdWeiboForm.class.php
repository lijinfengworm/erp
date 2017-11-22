<?php

/**
 * TrdWeibo form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdWeiboForm extends BaseTrdWeiboForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['publish_date']);
      unset($this['link_url']);
        
    $this->setWidget('content', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 6)));
    $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array()));

    $this->setWidget('advance_date', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20)));
    $this->setWidget('link_url', new sfWidgetFormInput(array(), array('size' => 50)));
    $this->setValidator('link_url', new sfValidatorUrl(array('required' => false, 'trim' => true), array( 'invalid' => 'url格式错误')));
    $this->setValidator('advance_date', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
    $this->setWidget('img_path', new sfWidgetFormInputFileEditable(array(
                'file_src' => $this->getObject()->getImgPath(),
                'is_image' => true,
                'edit_mode' => $this->getObject()->getImgPath(),
                'template' => '<div>%input%%file%</div>'
            )));
    $this->setValidator('img_path', new sfValidatorFile(array(
        'validated_file_class' => 'dateValidatedFile', 
        'required' => false, 'max_size' => 5000000,
        'path' => sfConfig::get('sf_upload_dir') . '/trade/weibo/' . date('ymd') . '/',
        'mime_types' => 'web_images'),array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M')));
    $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
        
  }
  
  public function myCallback($validator, $values) {
        $content = $values['content'];
        $newcontent = preg_replace("(http://[-a-zA-Z0-9:%_/+.~#?&//=]+)",'\\0'.'?from=shihuoweibo_201402181805',$content);
      
        $length = (mb_strlen($newcontent, 'utf-8') + strlen($newcontent)) / 2;
        if ($length >280){
            throw new sfValidatorError($validator, '已超140个字长度！');
        }
        return $values;
    }
  
   public function processValues($values) {
      if (isset($values['img_path']) && is_object($values['img_path'])) {
            $img_path1 = substr($values['img_path']->getPath(), 0, strlen($values['img_path']->getPath()) - 7);
        }		
        $values = parent::processValues($values);
        if (isset($values['img_path']) && $values['img_path'] && isset($img_path1)) {
            $values['img_path'] = 'http://c'.mt_rand(1,2).'.hoopchina.com.cn/uploads/trade/weibo/'. $values['img_path'];
        }
        return $values;
    }
}
