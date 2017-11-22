<?php

/**
 * TrdGoodsNotice form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsNoticeForm extends BaseTrdGoodsNoticeForm
{
    public static $status = array(
        0=>'未展示',
        1=>'展示',
        2=>'忽略',
    );

    public static $tagType = array(
        1=>'潮流配色',
        2=>'新货上架',
        3=>'白菜价',
    );

    public static $type = array(
        1=>'跑步',
        2=>'休闲',
    );
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
    //  unset($this['supplier_id']);

      $this->setWidget("tag_type", new sfWidgetFormChoice(array('expanded' => true,"choices" => TrdGoodsNoticeForm::$tagType),array('class'=>'radio')));
      $this->setValidator('tag_type', new sfValidatorChoice(array('choices'=>array_keys(TrdGoodsNoticeForm::$tagType), 'required' => true), array('invalid' => '请设置动态标签', 'required'=>'请设置动态标签')));

      $this->setWidget('goods_id', new sfWidgetFormInputHidden(array(), array('size' => 50)));
      $this->setWidget('status', new sfWidgetFormInputHidden(array(), array('size' => 50)));

      $this->setWidget("type", new sfWidgetFormChoice(array('expanded' => true,'multiple' => 'true',"choices" => TrdGoodsNoticeForm::$type),array('class'=>'radio')));
   //   $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(TrdGoodsNoticeForm::$type), 'required' => true), array('invalid' => '请选择运动场景', 'required'=>'请选择运动场景')));

      # 上传图片
      $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          //    'height'=>400,
          //    'width'=>400,
          'path'=>'trade/goods/notice',
     //     'ratio'=>'1x1'
      );
      $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
      $this->setWidget('pic', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
      $this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));
      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values)
    {
        # 运动场景验证
        if(!empty($values['type']))
        {
            $types = explode(',',$values['type']);
            foreach($types as $v)
            {
                if(empty(TrdGoodsForm::$type[$v]))
                {
                    throw new sfValidatorError($validator, '运动场景参数有误');
                }
            }
        }
        else
        {
            throw new sfValidatorError($validator, '请选择运动场景');
        }

        if(empty($values['goods_id']))
        {
            throw new sfValidatorError($validator, '缺少关联商品');
        }
        return $values;
    }
}
