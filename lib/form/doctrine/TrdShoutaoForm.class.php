<?php

/**
 * TrdShoutao form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdShoutaoItemForm extends BaseTrdShoutaoForm
{
    public static $types = array(
        0=>'单品',
        1=>'帖子',
    );
  public function configure()
  {
      unset($this['type']);
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['tid']);
      unset($this['item_url']);
      unset($this['admin_id']);
      unset($this['content_img']);
      unset($this['sengd_time']);

      # 宝贝id
      $this->setWidget('item_id', new sfWidgetFormInput(array(), array('size' => 50,)));
      $this->setValidator('item_id', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => 'id必填',  'min_length' => '标题不少于8个字')));

      # 宝贝标题
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50,)));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '标题必填')));


//      # 宝贝url
//      $this->setWidget('item_url', new sfWidgetFormInput(array(), array('size' => 50,)));
//      $this->setValidator('item_url', new sfValidatorString(array('required' => true, 'trim' => true,  'min_length' => 8), array('required' => 'URL必填',  'min_length' => '标题不少于8个字')));

      # 上传图片
      $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          //    'height'=>400,
          //    'width'=>400,
          'path'=>'uploads/trade/coupon',
          'ratio'=>'1x1'
      );
      $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule,'shoutao'=>true)));
      $this->setWidget('pic', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
      $this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '封面图片必填', 'invalid' => '封面图片必填')));

      # 推荐理由
      $this->setWidget('recommend', new sfWidgetFormTextarea(array(), array('size' => 200,)));
      $this->setValidator('recommend', new sfValidatorString(array('required' => true, 'trim' => true, ), array('required' => '必填',  'max_length' => '不大于140个字', 'min_length' => '不少于8个字')));

      # 标签
      $this->setWidget('tags', new sfWidgetFormInput(array(), array('size' => 50,)));
      $this->setValidator('tags', new sfValidatorString(array('required' => false, 'trim' => true), array('required' => '标题必填',  'max_length' => '不大于140个字', 'min_length' => '不少于8个字')));

      $this->widgetSchema->setHelps(array(
          'tags' => '<span style="color: red">标签分割请用半角逗号"，",例如"保健品，健美，魅力"</span>',

      ));

  }
}



class TrdShoutaoPostForm extends BaseTrdShoutaoForm
{
    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['tid']);
        unset($this['item_id']);
        unset($this['item_url']);
        unset($this['type']);
        unset($this['admin_id']);
        unset($this['sengd_time']);

        # 宝贝标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50,)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, ), array('required' => '标题必填',)));


//        # 描述链接
//        $this->setWidget('item_url', new sfWidgetFormInput(array(), array('size' => 50,)));
//        $this->setValidator('item_url', new sfValidatorString(array('required' => true, 'trim' => true,  'min_length' => 8), array('required' => 'URL必填',  'min_length' => '标题不少于8个字')));

        # 上传图片
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            //    'height'=>400,
            //    'width'=>400,
            'path'=>'uploads/trade/coupon',
            'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule,'shoutao'=>true)));
        $this->setWidget('pic', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '封面图片必填', 'invalid' => '封面图片必填')));


        $this->setWidget('upload_path1',new tradeWidgetFormKupload(array("callback"=>"displayImage1(data.url);","rule"=>$rule,'shoutao'=>true)));
        $this->setWidget('content_img', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('content_img', new sfValidatorUrl(array('required' => false, 'trim' => true), array('required' => '图片必填', 'invalid' => '图片必填')));


        # 推荐理由
        $this->setWidget('recommend',new tradeWidgetFormUeditor(array('button_widget'=>true)));
        $this->setValidator('recommend', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));
        # 标签
        $this->setWidget('tags', new sfWidgetFormInput(array(), array('size' => 50,)));
        $this->setValidator('tags', new sfValidatorString(array('required' => false, 'trim' => true), array('required' => '必填',  'max_length' => '不大于140个字', 'min_length' => '不少于8个字')));

//        # 开始时间
//        $this->setWidget('send_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
//        $this->setValidator('send_time', new sfValidatorString(array('required' => false)));
//        $this->setDefault('send_time', date('Y-m-d H:i:s'));

        $this->widgetSchema->setHelps(array(
            'tags' => '<span style="color: red">标签分割请用半角逗号"，",例如"保健品，健美，魅力"</span>',
        ));

    }
}
