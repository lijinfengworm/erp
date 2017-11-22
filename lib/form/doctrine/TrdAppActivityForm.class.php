<?php

/**
 * TrdAppActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdAppActivityForm extends BaseTrdAppActivityForm
{
    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);

        # 标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'min_length' => 8), array('required' => '标题必填', 'min_length' => '标题不少于8个字')));

        # 跳转链接
        $this->setWidget('go_url', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('go_url', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '链接必填')));

        # 商家
        $this->setWidget('business', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('business', new sfValidatorString(array('trim' => true), array()));

        # 商家地址
        $this->setWidget('business_url', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('business_url', new sfValidatorString(array('trim' => true), array()));

        # 内容
        $this->setWidget('description',new sfWidgetFormTextarea());
        $this->setValidator('description', new sfValidatorString(array('required' => false, 'trim' => true)));
        # 上传图片
        $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          'path'=>'uploads/trade/app_coupon',
          'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        $this->setWidget('img_path', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 300)));
        $this->setValidator('img_path', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));
        # 数量
        $this->setWidget('quantity', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('quantity', new sfValidatorNumber(array('min' => 1,'required' => true, 'trim' => true), array('required' => '数量必填')));

        # 单位量词
        $this->setWidget('unit', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('unit', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '量词必填')));

        # 专享价
        $this->setWidget('price', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('price', new sfValidatorNumber(array('required' => true, 'trim' => true), array('required' => '价格必填')));

        # 原价
        $this->setWidget('original_price', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('original_price', new sfValidatorNumber(array('required' => true, 'trim' => true), array('required' => '价格必填')));

        # 开始时间
        $this->setWidget('start_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('start_time', new sfValidatorString(array('required' => true)));
        $this->setDefault('start_time', date('Y-m-d H:i:s'));

        # 领取人数
        $this->setWidget('limit', new sfWidgetFormInput(array(), array('size' => 10)));
        $this->setValidator('limit', new sfValidatorNumber(array('min' => 0)));

        $this->widgetSchema->setHelps(array(
            'img_path' => '<span style="color: red">图片比例必须1:1</span>',
            'limit' => '<span style="color: red">0则不限次数</span>',
            'go_url' => '<div>代购详情示例: shihuo://www.shihuo.cn?route=daigouDetail&pid=?&gid=?</div>
                <div>海淘详情示例: shihuo://www.shihuo.cn?route=haitaoDetail&id=?</div>
                <div>优惠详情示例: shihuo://www.shihuo.cn?route=youhuiDetail&id=?</div>
                <div>运动鞋详情示例: shihuo://www.shihuo.cn?route=shoesDetail&id=?</div>
                <div>发现详情示例:  shihuo://www.shihuo.cn?route=findDetail&id=?</div>
                <div>团购详情示例: shihuo://www.shihuo.cn?route=grouponDetail&id=?</div>'
        ));
        # 回调
        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
    }

    public function myCallback($validator, $values)
    {
        if (empty($values['go_url'])) {
            throw new sfValidatorError($validator, '请填写跳转链接');
        }
        $stime = strtotime($values['start_time']);
        if (empty($stime)) {
            throw new sfValidatorError($validator, '开始时间参数有误');
        }
        if ($values['limit'] > $values['quantity'] ) {
            throw new sfValidatorError($validator, '领取数量不能大于总数');
        }

        return $values;
    }
}
