<?php

/**
 * TrdShopInfo form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdShopInfoForm extends BaseTrdShopInfoForm
{
    public function configure()
    {
        # 店铺名称
        $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
        $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40,), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字')));
        # 店铺主人
        $this->setWidget('owner_name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));

        # 类型
        $t = TrdShopCategoryTable::getInstance()->createQuery()->where('status = 0')->fetchArray();
        if(!empty($t)) foreach($t as $v)
        {
            $cate[$v['id']] = $v['name'];
        }

        $this->setWidget('shop_category_id', new sfWidgetFormChoice(array('choices'=>$cate)));
        $this->setValidator('shop_category_id', new sfValidatorChoice(array('choices'=>array_keys($cate),'required' => true)));//验证

        # 描述
        $this->setWidget('memo',new tradeWidgetFormUeditor(array('button_widget'=>true)));

        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            //    'height'=>400,
            //    'width'=>400,
            'path'=>'uploads/trade/coupon',
            'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        $this->setWidget('logo', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('logo', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '图片必填', 'invalid' => '图片必填')));
        # 链接
        $this->setWidget('link', new sfWidgetFormInput(array(), array('size' => 50, )));
        $this->setValidator('link', new sfValidatorString(array('required' => true, 'trim' => true,  ), array('required' => 'link必填',  'max_length' => 'link不大于40个字', 'min_length' => 'link不少于8个字')));
        # 标签
        $this->setWidget('business', new sfWidgetFormInput(array(), array('size' => 50,)));
        $this->setValidator('business', new sfValidatorString(array('required' => true, 'trim' => true,  ), array('required' => 'business必填',  'max_length' => 'business不大于40个字', 'min_length' => 'business不少于8个字')));

        # hupuUid
        $this->setWidget('hupu_uid', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));

        # 状态显示
        $this->setWidget("status", new sfWidgetFormChoice(array('expanded' => true,"choices" => array(0=>'显示',1=>'隐藏')),array('class'=>'type_status radio')));
        $this->setValidator('status', new sfValidatorChoice(array('choices'=>array_keys(array(0=>'显示',1=>'隐藏')), 'required' => true), array('invalid' => '请设置是状态', 'required'=>'请设置是状态')));

        # 佣金
        $this->setWidget('charge', new sfWidgetFormInput(array(), array('size' => 50, )));
        $this->setValidator('charge', new sfValidatorString(array('required' => true, 'trim' => true,  ), array('required' => 'charge必填',  'max_length' => 'charge不大于40个字', 'min_length' => 'charge不少于8个字')));
        # 是否认证
        $this->setWidget("verify_status", new sfWidgetFormChoice(array('expanded' => true,"choices" => array(0=>'否',1=>'是')),array('class'=>'type_status radio')));
        $this->setValidator('verify_status', new sfValidatorChoice(array('choices'=>array_keys(array(0=>'否',1=>'是')), 'required' => true), array('invalid' => '请设置是状态', 'required'=>'请设置是状态')));

      //  $this->setDefault('collect_count', rand(0, 30));
    }

//    public function processValues($values) {
//        if(isset($values['logo']) && is_object($values['logo'])){
//            $imgpath = $values['logo']->getPath();
//        }
//
//        $values = parent::processValues($values);
//
//        if(isset($values['logo']) && isset($imgpath)){
//            $img = strtolower($imgpath . $values['logo']);
//
//            $i = new Imagick($img);
//            $i->thumbnailImage(216, 91);
//            $i->writeImages($img, true);
//        }
//
//        if(strstr($values["logo"], "/") == false && $values["logo"]) {
//            $values["logo"] = '/' . basename(sfConfig::get('sf_upload_dir')) . '/trade/shop/' . $values["logo"];
//        }
//
//        return $values;
//    }
}
