<?php

/**
 * TrdAppBigsale form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdAppBigsaleForm extends BaseTrdAppBigsaleForm
{
  public function configure()
  {
    unset($this['updated_at']);
    unset($this['created_at']);

    # 标题
    $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
    $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '标题必填')));

    # 跳转链接
    $this->setWidget('go_url', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
    $this->setValidator('go_url', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '链接必填')));

    # 上传banner图片
    $rule = array(
        'required'=>true,
        'max_size'=>'500000',
        'path'=>'uploads/trade/app_bigsale'
    );
    $this->setWidget('upload_banner_path',new tradeWidgetFormKupload(array("callback"=>"displayBannerImage(data.url);","rule"=>$rule)));
    $this->setWidget('banner_img_path', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 300)));
    $this->setValidator('banner_img_path', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => 'banner图片必填', 'invalid' => 'banner图片必填')));

    # 背景颜色
    $this->setWidget('background_color', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
    $this->setValidator('background_color', new sfValidatorString(array('trim' => true), array()));

    # 图片
    for ($i = 0; $i < 4; $i++) {
        $this->setWidget('upload_path' . $i,new tradeWidgetFormKupload(array("callback"=>"displayImage({$i},data.url);","rule"=>$rule)));
        $this->setWidget('img_path' . $i, new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 300)));
        $this->setValidator('img_path' . $i, new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '图片必填', 'invalid' => '图片必填')));
    }

    # 内容
    $this->setWidget('description',new tradeWidgetFormUeditor(array('button_widget'=>true)));
    $this->setValidator('description', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));

    # 专享价
    $this->setWidget('price', new sfWidgetFormInput(array(), array('size' => 10)));
    $this->setValidator('price', new sfValidatorNumber(array('required' => true, 'trim' => true), array('required' => '价格必填')));

    # 原价
    $this->setWidget('original_price', new sfWidgetFormInput(array(), array('size' => 10)));
    $this->setValidator('original_price', new sfValidatorNumber(array('required' => true, 'trim' => true), array('required' => '价格必填')));

    # 分享文案
    $this->setWidget('share_content', new sfWidgetFormTextarea(array(), array('size' => 100, 'maxlength' => 300)));
    $this->setValidator('share_content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '分享文案必填')));
  }
}
