<?php

/**
 * TrdActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdActivityForm extends BaseTrdActivityForm
{
    public static $root_type = array('0'=>'优惠券','1'=>'礼品卡','2'=>'实物礼品');
    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);


        # 标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 8), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字')));
        # 商城
        $this->setWidget('mart', new sfWidgetFormInput(array(), array('size' => 20, 'maxlength' => 100)));
        # 跳转链接
        $this->setWidget('receive_url', new sfWidgetFormInput());
        # 内容
        $this->setWidget('content',new tradeWidgetFormUeditor(array('button_widget'=>true)));
        $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));
        # 上传图片
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
        //    'height'=>400,
        //    'width'=>400,
            'path'=>'uploads/trade/coupon',
            'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        $this->setWidget('img_path', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('img_path', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));
        # 礼品数量
        $this->setWidget('total', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('total', new sfValidatorNumber(array('min'  => 1,'required' => true, 'trim' => true), array('required' => '礼品数量必填')));
        # 开始时间
        $this->setWidget('start_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('start_date', new sfValidatorString(array('required' => true)));
        $this->setDefault('start_date', date('Y-m-d H:i:s'));
        # 结束时间
        $this->setWidget('expiry_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('expiry_date', new sfValidatorString(array('required' => true)));
        $this->setDefault('expiry_date', date('Y-m-d H:i:s'));
        # 领取类型
        $this->setWidget('root_type', new sfWidgetFormChoice(array('choices'=>self::$root_type)));
        $this->setValidator('root_type', new sfValidatorChoice(array('choices'=>array_keys(self::$root_type),'required' => true)));//验证
        # 卡号id
     //   $this->setWidget('card_ids', new sfWidgetFormTextarea());
        # 兑换要求积分
        $this->setWidget('integral', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 100)));
        $this->setValidator('integral', new sfValidatorNumber());
        # 兑换要求金币
        $this->setWidget('gold', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('gold', new sfValidatorNumber());
        # 领取人数
        $this->setWidget('limits', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('limits', new sfValidatorNumber(array('min'  => 1,)));

       // $this->setValidator('type', new sfValidatorChoice(array('choices'=>array('0'=>'0','1'=>'1','2'=>'2'))));
       // $this->setValidator('exchange_type', new sfValidatorChoice(array('choices'=>array('0'=>'0','2'=>'2','3'=>'3','4'=>'4')), array('required' => '兑换规则必选','invalid' => '兑换规则必选')));

        $this->widgetSchema->setHelps(array(
                'img_path' => '<span style="color: red">图片比例必须1:1</span>',
                'root_type' => '<span style="color: red">PS：请勿把发给第三方的礼品卡导入到活动当中，以免造成礼品卡重复领取</span>',
          ));
        # 回调
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );

       // $this->getWidgetSchema()->moveField('title',sfWidgetFormSchema::BEFORE,'id');
    }
  
  public function myCallback($validator, $values)
  {
        if($values['root_type'] == 0 && (empty($values['mart']) || empty($values['receive_url'])) )
        {
            throw new sfValidatorError($validator, '请填写商城描述或跳转链接');
        }

        $stime = strtotime($values['start_date']);
        $etime = strtotime($values['expiry_date']);
        if( empty($stime) || empty($etime) )
        {
            throw new sfValidatorError($validator, '时间限制参数有误');
        }
        if( $stime >= $etime )
        {
            throw new sfValidatorError($validator, '开始时间必须小于结束时间');
        }

;       if(!empty($values['integral']) && !empty($values['gold']))
        {
            # 要求积分和金币
            $values['exchange_type'] = 0;
        }
        elseif(!empty($values['integral']))
        {
            # 只要求积分
            $values['exchange_type'] = 2;
        }
        elseif(!empty($values['gold']))
        {
            # 只要求金币
            $values['exchange_type'] = 3;
        }
        else
        {
            # 免费
            $values['exchange_type'] = 4;
        }

        if( $values['limits'] > $values['total'] )
        {
            throw new sfValidatorError($validator, '领取数量不能大于总数');
        }

        return $values;
    }
    
  public function processValues($values) {
      if (isset($values['img_path']) && is_object($values['img_path'])) {
            $img_path1 = substr($values['img_path']->getPath(), 0, strlen($values['img_path']->getPath()) - 7);
        }		
        //$this->default_tag = $values['voice_tags_list'][0];
        $values = parent::processValues($values);
        if (isset($values['img_path']) && $values['img_path'] && isset($img_path1)) {
            $img_path2 = 'http://c'.mt_rand(1,2).'.hoopchina.com.cn/uploads/trade/activity/'. $values['img_path'];
            $this->reziseImgPath($values, $img_path1);
            $values['img_path'] = 'http://c'.mt_rand(1,2).'.hoopchina.com.cn/uploads/trade/activity/'. substr($values['img_path'], 0, 6) . '/thumbnail-' . substr($values['img_path'], 7);
            $imageinfo = getimagesize($img_path2);
            if ($imageinfo){
                $values['width'] = $imageinfo[0];
                $values['height'] = $imageinfo[1];
            }
        }
        $uid = (sfContext::getInstance()->getConfiguration()->getApplication() == 'backend' ? '' : '-' ) . sfContext::getInstance()->getUser()->getAttribute('uid');
        $username = sfContext::getInstance()->getUser()->getAttribute('username');
        if ($this->isNew()) {
            $values['grant_uid'] = $uid;
            $values['grant_username'] = $username;
        }
        return $values;
    }


    protected function reziseImgPath($values, $path) {
        $img = $path . $values['img_path'];
        $thumbnail = substr($values['img_path'], 0, 7) . 'thumbnail-' . substr($values['img_path'], 7);
        $thumbnail480 = substr($values['img_path'], 0, 7) . 'thumbnail480-' . substr($values['img_path'], 7);
        //$bmiddle = substr($values['img_path'], 0, 7) . 'bmiddle-' . substr($values['img_path'], 7);

        //thumbnail缩略图
        $thumb_width = 160;
        $thumb_height = 160;
        $img1 = new Imagick($img);
        
        $data = $img1->getImageGeometry();
        $format = strtolower($img1->getImageFormat());
        if ($format == 'gif') {
            new imgResize($img, $path . $thumbnail, $thumb_width, $thumb_height, 1, 2);
        } else {
            $img1->thumbnailImage($thumb_width, $thumb_height);
            $img1->writeImages($path . $thumbnail, true);
        }
    }

}
