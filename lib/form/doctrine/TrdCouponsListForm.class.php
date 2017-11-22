<?php

/**
 * TrdCouponsList form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdCouponsListForm extends BaseTrdCouponsListForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
        
      $this->widgetSchema->setHelps(array(
          'amount' => '<span style="color: red">优惠券的额度，如：60元</span>',
          'img_path' => '<span style="color: red">必须是text文档</span>',
      ));
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setWidget('mall', new sfWidgetFormChoice(array('choices'=>array('1'=>'优购网','2'=>'亚马逊中国','3'=>'京东','9'=>'其他'))));//商品销售状态
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 50), array('required' => '标题必填',  'max_length' => '副标题不大于20个字')));
      $this->setValidator('mall', new sfValidatorChoice(array('choices'=>array('1'=>'1','2'=>'2','3'=>'3','9'=>'9'))));
      
      $this->setWidget('img_path', new sfWidgetFormInputFileEditable(array(
                'file_src' => '',
                'is_image' => false,
                'edit_mode' => '',
                'template' => '<div>%input%%file%</div>'
            )));
      $this->setValidator('img_path', new sfValidatorFile(array(
        'validated_file_class' => 'dateValidatedFile', 
        'required' => false, 'max_size' => 5000000,
        'path' => sfConfig::get('sf_upload_dir') . '/trade/coupons/' . date('ymd') . '/',
        'mime_types' => 'web_file'),array('mime_types' => '文件格式不正确', 'max_size' => '文件最大5M')));
      
        $this->setWidget('expiry_date', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20)));
        $this->setValidator('expiry_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
        
        $this->setWidget('total', new sfWidgetFormInputHidden());
        $this->setWidget('recevied', new sfWidgetFormInputHidden());
        $this->setWidget('start_date', new sfWidgetFormInputHidden());
        $this->setDefault('expiry_date', date('Y-m-d H:i:s'));
        
  }
  
 
  public function processValues($values) {
//      $falg = false;
//      if (isset($values['img_path']) && is_object($values['img_path'])) {
//            $falg = true;
//        }		
        //$this->default_tag = $values['voice_tags_list'][0];
        $values = parent::processValues($values);
//        $content = file_get_contents(sfConfig::get('sf_upload_dir') . '/trade/coupons/' . $values['img_path']);
//        var_dump($content);die;
//        if ($falg) {
//            $values['img_path'] = 'http://c'.mt_rand(1,2).'.hoopchina.com.cn/uploads/trade/coupons/'. $values['img_path'];
//            
//
//        }
        if($this->isNew()){
            $values['start_date'] = date('Y-m-d H:i:s',time());
        }
        return $values;
    }
    
    
}
