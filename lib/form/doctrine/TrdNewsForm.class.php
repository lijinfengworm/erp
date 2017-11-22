<?php

/**
 * TrdNews form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdNewsForm extends BaseTrdNewsForm
{
  private $separator = ',';
  private $exp_sina = '/(https{0,1}:?\/\/)?t.cn\/\w+/is';

  private  $_defautl_type = array(
      1 => '是',
      0 => '否'
  );

  
  public function configure()
  {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['is_shopping']);
        unset($this['attr']);
        //$this->useFields(array('id'));
       //unset($this->widgetSchema['brand_id']);
        //优惠默认信息
        $info = $this->getOption("info");

        //是否开启审核
        $is_audit = $this->getOption("is_audit");
        $auditMessage = $this->getOption("auditMessage");
        if(empty($is_audit)) {
            unset($this['audit_user']);
            unset($this['audit_status']);
            unset($this['audit_message']);
            unset($this['audit_date']);
        } else {
            $this->setWidget('audit_status', new sfWidgetFormChoice(array('expanded' => true,'default'=>"1",
                'choices'=>array(1=>'通过',4=>'修改后通过',5=>'退回',3=>'拒绝')),array('class'=>'audit_status radio')));//类型
            $this->setValidator('audit_status', new sfValidatorChoice(
                array('choices'=>array('0'=>'1','1'=>'4',2=>5,3=>'3')),array('required' => '审核状态必填')));

            $this->setWidget('audit_message', new sfWidgetFormChoice(array(
                'choices'=>$auditMessage),array('class'=>' ')));//类型
            $this->setValidator('audit_message', new sfValidatorChoice(
                array('choices'=>array_keys($auditMessage)),array('required' => '拒绝信息必填')));
        }

        //分类
        $menuTable   = TrdMenuTable::getInstance();
        $this->root_menus = $menuTable->getRootMenu(1,'');
        
         //根目录
        $menu_root_array = array();
        $menu_root_value = array();
        $menu_root_array[0] = '请选择一级分类';
        $menu_root_value[0] = '';
        foreach($this->root_menus as $root) {
            $menu_root_array[$root->getId()] = $root->getName();
            $menu_root_value[$root->getId()] = $root->getId();
        }
        $root_id = $this->getObject()->getRootId() ? $this->getObject()->getRootId() : 0;
         //子目录
        $menu_children_array = array();
        $menu_children_value = array();
        $menu_children_array[0] = '请选择二级分类';
        $menu_children_value[0] = '';

        if ($root_id){
            $this->children_menus = $menuTable->getChildrenMenu('',$root_id);
            foreach($this->children_menus as $children) {
                $menu_children_array[$children->getId()] = $children->getName();
                $menu_children_value[$children->getId()] = $children->getId();
            }
        }

        //获取商城标签
        $store_type = $this->getObject()->getType() ? ($this->getObject()->getType() == 1 ? 1 : 0) : 1;     //国内商场的type为1，海淘为0
        $store_array = array();
        $store_array[0] = '请选择所属商城';
        $storeObj = TrdStoreTable::getAllStoreByType($store_type);
        if ($storeObj){
            foreach ($storeObj as $store){
                $store_array[$store->getId()] = $store->getName();
            } 
        }
        $this->widgetSchema->setHelps(array(
            'intro' => '<span style="color: red">简介必填，长度不能大于200个字符，发布必须要有一张图片</span>',
            'orginal_type' => '<span style="color: red">跳转网站url、跳转网站必填</span>',
            'trd_product_tag_list'=>'<span style="color: red">为了识货的未来，请填写最精准tag，多个tag中间用(,)</span>',
            'price'=>'<span style="color: red">价格为必填项</span>',
            'img_path'=>'<span style="color: red">图片大小为400x400</span>',
            'img_attr'=>'<span style="color: red">可传多张</span>',
            'timing_interval'=>'如果要定时发布，就填写具体的时间，否则记得留空！',
            'publish_date'=>'如果没有特殊需求，默认即可，定时发布已经不依赖于此时间',
            'text'=>'图片不能超过600px',
        ));
        $this->setWidget('title', new sfWidgetFormInput(array(), array('placeholder'=>'','class'=>'calibration w460','size' => 50, 'maxlength' => 100)));



      $this->setWidget('subtitle', new sfWidgetFormInputText(array(), array('placeholder'=>'','class'=>'calibration w240','size' => 25)));//副标题
        $this->setWidget('spreadtitle', new sfWidgetFormInputText(array(), array('placeholder'=>'','class'=>'calibration','size' => 50, 'maxlength' => 100)));//副标题
        //$this->setWidget('direct_words', new sfWidgetFormInputText(array(), array('size' => 25)));//商品图片说明文字
        $this->setWidget('goods_state', new sfWidgetFormChoice(array('expanded' => true,'choices'=>array('0'=>'正在进行','1'=>'售罄','2'=>'已过期')),array('class'=>'radio')));//商品销售状态
        $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>array('0'=>'请选择类型','1'=>'国内优惠','2'=>'海淘专区')),array('onchange' => 'getStoreByType(this.value)')));//商品类型


      //品牌text
      $this->setWidget('brand_text', new sfWidgetFormInputText(
          array('default'=>$this->getBrnadName($this->getObject()->getBrandId())),
          array('id'=>'news_brand_id','autocomplete'=>'off')));


      //品牌
        $this->setWidget('brand_id', new sfWidgetFormInputHidden(array()));


      $this->setWidget('store_id', new sfWidgetFormChoice(array('choices'=>$store_array)));//所属商城

        
        $this->setWidget('is_display_index', new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1)));
        $this->setValidator('is_display_index', new sfValidatorPass());

        $this->setValidator('subtitle', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40), array('required' => '副标题必填',  'max_length' => '副标题不大于20个字')));
        $this->widgetSchema->setHelp('subtitle','<span style="color: red">副标题必填，长度小于20个字符</span>');

      //$this->setValidator('direct_words', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '商品图片链接文字必填',  'max_length' => '商品图片链接文字不大于20个字')));

        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 12), array('required' => '标题必填',  'max_length' => '标题不大于20个字', 'min_length' => '标题不少于12个字')));

        $this->widgetSchema->setHelp('title','<span style="color: red">标题必填，长度在12-40个字符</span>');

        $this->setValidator('spreadtitle', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 25, 'min_length' => 5), array('required' => '推广标题必填',  'max_length' => '推广标题不大于25个字', 'min_length' => '推广标题不少于5个字')));
        $this->widgetSchema->setHelp('spreadtitle','<span style="color: red">推广标题必填，长度在5-25个字符</span>');
        $this->setWidget('intro', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 6, 'onkeyup' => 'count()')));
        $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true,'min_length' => 50, 'max_length' => 500), array('required' => '简介必填', 'max_length' => '简介不能大于500个字')));
        $this->widgetSchema->setHelp('intro','<span style="color: red">简介必填，长度不能小于50个字，而其不能大于500个字符，发布必须要有一张图片</span>');
        
        $this->setWidget('show_intro', new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1)));
        $this->setValidator('show_intro', new sfValidatorPass());  
                
        //$this->setWidget('text', new sfWidgetFormCKEditor(array(),array()));
        //替换文中的widget
        if($this->getObject()->getText()){
             $this->getObject()->setText($this->_pattern($this->getObject()->getText(), 'reverse'));
        }
        $this->setWidget('text',new tradeWidgetFormUeditor(array('button_widget'=>true, 'channel'=>'news')));
       // $this->setWidget('text',new tradeWidgetFormUmeditor());
        $this->setValidator('text', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));

        $this->setWidget('price',new sfWidgetFormInput(array(), array('class'=>'w80','size' => 50, 'maxlength' => 1000)));


        $this->setWidget('orginal_url', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 1000)));
        $this->setWidget('orginal_type', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));
        $this->setWidget('product_id', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));
        $this->setWidget('tagsList', new sfWidgetFormChoice(array('choices' => $this->getDefaultTagsChoice(), 'multiple' => true, 'expanded' => true), array('onclick' => 'attachToTags_list(this, event)')));
        $this->setValidator('tagsList', new sfValidatorPass());


        $this->setWidget('trd_product_tag_list', new myTradeTagListInputWidget(array('separator' => $this->separator), array('replace'=>'，'),array('size' => 50, 'onkeyup' => 'getParentTagName(event)')));

      $this->setWidget('publish_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setWidget('timing_interval', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-%d'})",'maxlength' => 19, 'size' => 20)));

      $this->setWidget('revel_start_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setWidget('revel_end_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));

      #封面图
      $rule = array(
          'required'=>true,
          'path'=>'newsIndex',
          'max_size'=>'500000',
          'min_width'=>200,
          'min_height'=>200
      );
      $this->setWidget('img_path', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
      $this->setWidget('img_path_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_news_img_path",data.url);',"rule"=>$rule)));
      $this->setValidator('img_path', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));

      #封面图end

      $this->setValidator('publish_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));

      /*  设置定时时间  */
      $this->setValidator('timing_interval', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      $this->setDefault('timing_interval','');
      if(!$this->isNew()) {
          $_timingInterval = $this->getObject()->getTimingInterval();
          if(!empty($_timingInterval)) {
              $this->getObject()->setTimingInterval(date('Y-m-d H:i:s', $this->getObject()->getTimingInterval()));
          } else {
              $this->getObject()->setTimingInterval('');
          }
      }



      $this->setValidator('revel_start_date', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      $this->setValidator('revel_end_date', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));

        $this->setValidator('orginal_url', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '跳转网站url必填', 'invalid' => '跳转网站url格式错误')));
        $this->setValidator('orginal_type', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '跳转网站必填', 'invalid' => '跳转网站不能大于20个字')));
        $this->setValidator('product_id', new sfValidatorInteger(array('required' => false)));
        
        $this->setWidget('author_id', new sfWidgetFormInputHidden());
        $this->setWidget('editor_id', new sfWidgetFormInputHidden());
        if (sfContext::getInstance()->getConfiguration()->getApplication() == 'backend'){
            $this->setWidget('hits', new sfWidgetFormInputHidden());
        } else {
            unset($this['hits']);
        }
        $this->setWidget('reply_count', new sfWidgetFormInputHidden());
        $this->setWidget('support', new sfWidgetFormInputHidden());
        $this->setWidget('against', new sfWidgetFormInputHidden());
        $this->setWidget('shoe_id', new sfWidgetFormInputHidden());
        $this->setWidget('item_all_id', new sfWidgetFormInputHidden());
        $this->setWidget('baoliao_id', new sfWidgetFormInputHidden());
        $this->setWidget('height', new sfWidgetFormInputHidden());
        $this->setWidget('width', new sfWidgetFormInputHidden());


      //推荐到首页
        if (!empty($info) && $info->getIntro()) $this->setDefault('intro', $info->getIntro());
        if (!empty($info) && $info->getTitle()) $this->setDefault('title', $info->getTitle());
        if (!empty($info) && $info->getSubtitle()) $this->setDefault('subtitle', $info->getSubtitle());
        if (!empty($info) && $info->getOrginalUrl()) $this->setDefault('orginal_url', $info->getOrginalUrl());
        if (!empty($info) && $info->getOrginalType()) $this->setDefault('orginal_type', $info->getOrginalType());
        if (!empty($info) && $info->getShoeId()) $this->setDefault('shoe_id', $info->getShoeId());
        if (!empty($info) && $info->getItemAllId()) $this->setDefault('item_all_id', $info->getItemAllId());
        if (!empty($info) && $info->getBaoliaoId()) $this->setDefault('baoliao_id', $info->getBaoliaoId());
        
        //加入分类
        $this->setWidget("root_id", new sfWidgetFormChoice(array("choices" => $menu_root_array), array('onchange' => 'getSecondMenuById(this.value)')));

      //商品库相关
      $commodity = $this->getObject()->getCommodity();
      $commodity = json_decode($commodity, true);
      $this->setWidget("commodity_desc", new sfWidgetFormInput(
          array(), array('placeholder'=>'','class'=>'calibration w460','size' => 50, 'maxlength' => 100)
      ));
      $this->widgetSchema->setHelp('commodity_desc','<span style="color: red">请简要说明款型、备货、功能、渠道优势等特点，字数控制在12与40之间</span>');
      $this->setWidget("commodity_goods_id", new sfWidgetFormInput());
      $this->setWidget("commodity_goods_name", new sfWidgetFormInput());

      $this->setDefault('commodity_desc', $commodity['desc']);
      $this->setDefault('commodity_goods_id', $commodity['goods_id']);
      $this->setDefault('commodity_goods_name', $commodity['goods_name']);

      $this->setValidator('commodity_desc', new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 40, 'min_length' => 12), array('required' => '描述必填',  'max_length' => '描述不大于40个字', 'min_length' => '描述不少于12个字')));
      $this->setValidator('commodity_goods_id', new sfValidatorString(
              array('required' => false))
      );
      $this->setValidator('commodity_goods_name', new sfValidatorString(
              array('required' => false))
      );

      //图片集合
      $imgAttr = $this->getObject()->getImgAttr();
      $imgAttr = json_decode($imgAttr, true);
      $this->getObject()->setImgAttr($imgAttr);
      @$this->setWidget('img_attr', new sfWidgetFormInputCheckbox(array(), array('class'=>'w240 J_date')));
      $this->setWidget('img_attr_btn',new tradeWidgetFormKupload(array("callback"=>'callbackAttr("trd_news_img_attrs",data.url);',"rule"=>$rule)));
      $this->setValidator('img_attr', new sfValidatorPass());

        //是否展示评论
      $this->setWidget("is_show_comment", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_defautl_type,'default'=>1),array('class'=>'audit_status radio')));
      $this->setDefault('is_show_comment',1);
      $this->setValidator('is_show_comment', new sfValidatorChoice(array('choices'=>array_keys($this->_defautl_type), 'required' => true), array('invalid' => '请设置是否开启评论', 'required'=>'请设置是否开启评论')));

      //是否展示购买链接
      $this->setWidget("is_show_buy_link", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_defautl_type,'default'=>1),array('class'=>'audit_status radio')));
      $this->setDefault('is_show_buy_link',1);
      $this->setValidator('is_show_buy_link', new sfValidatorChoice(array('choices'=>array_keys($this->_defautl_type), 'required' => true), array('invalid' => '请设置是否开启购买链接', 'required'=>'请设置是否开启购买链接')));

      //是否详情图片加链接
      $attr =  $this->getObject()->getAttr() ? unserialize($this->getObject()->getAttr()) : array();
      $is_text_img_link = isset($attr['is_text_img_link']) ? $attr['is_text_img_link'] : 1;
      $this->setWidget("is_text_img_link", new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_defautl_type,'default'=>1),array('class'=>'audit_status radio')));
      $this->setDefault('is_text_img_link', $is_text_img_link);
      $this->setValidator('is_text_img_link', new sfValidatorChoice(array('choices'=>array_keys($this->_defautl_type), 'required' => true), array('invalid' => '请设置是否开启详情图片加链接', 'required'=>'请设置是否开启详情图片加链接')));

      $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));

        //文章类型
      $this->setWidget("root_type", new sfWidgetFormChoice(array("choices" => array(1=>'文章', 2=>'公告'))));

      $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
      $this->setDefault('publish_date', date('Y-m-d H:i:s'));
      /*
        if($this->isNew()){
            $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
            );
        }*/

      //验证
      $root_type = $this->getOption("root_type");
      if($root_type == 1){
          $this->setValidator('type', new sfValidatorChoice(
              array('choices'=>array('0'=>'','1'=>'1','2'=>'2','required'=>true)),
              array('required' => '类型必填','invalid' => '类型必填'))
          );

          $this->setValidator('store_id', new sfValidatorChoice(
                  array('choices'=>array_keys($store_array),'required' => false))
          );

          $this->setValidator('root_id', new sfValidatorChoice(
              array('choices'=>$menu_root_value, 'required' => true),
              array('invalid' => '请选择分类', 'required'=>'请选择分类'))
          );

          $this->setValidator('price', new sfValidatorString(
                  array('required' => true, 'trim' => true),
                  array('required' => '价格必填'))
          );

          $this->setValidator('brand_id', new sfValidatorString(
              array('required' => false))
          );

          $this->setValidator('brand_text', new sfValidatorString(
                  array('required' => true),array('required' => '品牌必填!'))
          );

          $this->setValidator('goods_state', new sfValidatorChoice(
              array('choices'=>array('0'=>'0','1'=>'1','2'=>'2'),'required' => true))
          );

          $this->setValidator('trd_product_tag_list', new myTradeTagListInputValidator(
              array('model' => 'TrdProductTag', 'required' => 'tag必填', 'separator' => $this->separator,'replace'=>'，'))
          );
      }else{
          $this->setValidator('type', new sfValidatorString(
                  array('required'=>false),
                  array('required' => '类型必填','invalid' => '类型必填'))
          );

          $this->setValidator('store_id', new sfValidatorString(
                  array('required' => false))
          );
          $this->setValidator('store_id', new sfValidatorChoice(
                  array('choices'=>array_keys($store_array),'required' => false))
          );

          $this->setValidator('root_id', new sfValidatorString(
                  array( 'required' => false))
          );

          $this->setValidator('price', new sfValidatorString(
                  array('required' => false, 'trim' => true),
                  array('required' => '价格必填'))
          );

          $this->setValidator('brand_id', new sfValidatorString(
                  array('required' => false))
          );

          $this->setValidator('brand_text', new sfValidatorString(
                  array('required' => false),array('required' => '品牌必填!'))
          );

          $this->setValidator('goods_state', new sfValidatorString(
                  array('required' => false))
          );

          $this->setValidator('trd_product_tag_list', new myTradeTagListInputValidator(
                  array('required' => false,'model' => 'TrdProductTag', 'required' => 'tag必填', 'separator' => $this->separator,'replace'=>'，'))
          );
      }
  }
  
  public function myCallback($validator, $values) {
        if (empty($values['root_id']) && $values['root_type'] == 1){
            throw new sfValidatorError($validator, '请选择分类');
        } else if (empty($values['children_id']) && $values['root_type'] == 1){
            $menuTable   = TrdMenuTable::getInstance();
            $childrencount = $menuTable->getChildrenMenuCount('',$values['root_id']);
            if ($childrencount){
                throw new sfValidatorError($validator, '请把分类补充完整');
            }
        }

        //判断品牌
        if($brand_text = trim($values['brand_text'])){
            $values['brand_id'] = TrdNewsBrandsTable::getInstance()->createBrand($brand_text);
        }


       //图片详情判断
      if($values['img_attr']){
          $img_attr = $values['img_attr'];
          if(count($img_attr) > 5){
              $errorSchema = new sfValidatorErrorSchema($validator);
              $errorSchema->addError(new sfValidatorError($validator, '图片最多不超过五张' ), 'img_attr');
              throw $errorSchema;
          }
      }

      //商品库验证
      if($values['commodity_goods_id'] && $values['commodity_goods_name'] && !$values['commodity_desc']){
          $errorSchema = new sfValidatorErrorSchema($validator);
          $errorSchema->addError(new sfValidatorError($validator, '描述必填' ), 'commodity_desc');
          throw $errorSchema;
      }

      return $values;
    }
    
  public function processValues($values) {
        $values = parent::processValues($values);
        if ($values['type'] == 1){
            $values['is_display_index'] = 1;
        }

        //判断发布人
        if(sfContext::getInstance()->getConfiguration()->getApplication() == 'backend') {
            $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
        } else if (sfContext::getInstance()->getConfiguration()->getApplication() == 'tradeadmin') {
            $uid = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
            //新增的时候 添加发布人
            if ($this->isNew()) {
                $_attr = $this->getObject()->getAttr();
                if (!empty($_attr)) {
                    $_attr = unserialize($_attr);
                }
                $_attr['create_user'] = sfContext::getInstance()->getUser()->getTrdUsername();
                $values['attr'] = serialize($_attr);
            }
        } else {
            $uid = '-'.sfContext::getInstance()->getUser()->getAttribute('uid');
        }
         if ($this->isNew()) {
            $values['author_id'] = $uid;
        }
        $values['editor_id'] = $uid;

      //判断是不是定时发布
      if(!empty($values['timing_interval']) && strtotime($values['timing_interval']) !== false) {
          $values['is_delete'] = 2;
          $values['timing_interval'] = strtotime($values['timing_interval']);
      }

        //新增的时候 如果没有设置审核 默认通过审核
        if ($this->isNew()) {
            $values['audit_status'] = $this->getOption('audit_status') ? $this->getOption('audit_status') : 1;
            if(empty($values['audit_user'])) $values['audit_user'] = 0;
         }
         //如果是审核模式
        if($this->getOption("is_audit")) {
             if($values['audit_status'] == 3) {  //如果是拒绝
                 $auditMessage = $this->getOption('auditMessage');
                 if (!empty($values['audit_message'])) {
                     $values['audit_message'] = $auditMessage[$values['audit_message']];
                 } else {
                     $_info = sfContext::getInstance()->getRequest()->getParameter('other_message');
                     if (!empty($_info)) {
                         $values['audit_message'] = $_info;
                     } else {
                         $values['audit_message'] = '未填写拒绝理由';
                     }
                 }
             }else if($values['audit_status'] == 5) {  //如果是退回

                $_info = sfContext::getInstance()->getRequest()->getParameter('other_message');
                if (!empty($_info)) {
                    $values['audit_message'] = $_info;
                } else {
                    $values['audit_message'] = '未填写退回理由';
                }

            }  else {
                 $values['audit_message'] = '通过审核';
             }
             $values['audit_user'] = sfContext::getInstance()->getUser()->getTrdUserId();
             $values['audit_date'] = date('Y-m-d H:i:s',time());
        }


        //如果是回退重新提交模式
      if($this->getOption("is_repair")) {
          $values['audit_status'] = 2;
          $values['audit_message'] = $values['audit_user'] = $values['audit_date'] = '';
      }

        //根据productid插入代购的开始和结束时间
        if($values['type'] == 2 && $values['product_id']){
            $item = TrdProductAttrTable::getInstance()->find($values['product_id']);
            if ($item && $item->getStatus() == 0 && $item->getShowFlag() == 1){
                $values['product_start_date'] = $item->getStartDate();
                $values['product_end_date'] = $item->getEndDate();
            } else {
                $values['product_id'] = 0;
                $values['product_start_date'] = 0;
                $values['product_end_date'] = 0;
            }
        }
      //过滤text
      if($values['text']){
          $xss = new XssHtml(
              trim($values['text']),
              'utf-8' ,
              array('a', 'img', 'br', 'strong', 'b', 'code', 'pre', 'p', 'div', 'em', 'span',  'h2',
                  'h3', 'h4', 'h5', 'h6', 'li', 'ul','widget_product','object','embed')
          );
          $xss->m_AllowAttr = array('title', 'src', 'href', 'id', 'class', 'style', 'width',
              'height', 'alt', 'target', 'align','price' ,'type', 'quality', 'allowscriptaccess','allowfullscreen');
          $values['text'] =  trim($xss->getHtml());
      }

      //替换文中的widget
      if($values['text']){
          $values['text'] = $this->_pattern($values['text']);
      }


      //是否详情图片加链接
      $_attr = $this->getObject()->getAttr();
      if ($_attr) {
          if (!empty($_attr)) {
              $_attr = unserialize($_attr);
          }else{
              $_attr = array();
          }

          $_attr['is_text_img_link'] = $values['is_text_img_link'];
          $values['attr'] = serialize($_attr);
      }

      //商品库相关
      $commodity = $this->getObject()->getCommodity();
      $commodity = json_decode($commodity, true);
      if($values['commodity_goods_id'] && $values['commodity_goods_name']){
          $commodity['goods_id']   =  $values['commodity_goods_id'];
          $commodity['goods_name'] =  $values['commodity_goods_name'];
      }else{
          $commodity['goods_id']   =  '';
          $commodity['goods_name'] =  '';
      }
      $commodity['desc'] =  $values['commodity_desc'];
      $values['commodity'] = json_encode($commodity);

      //图片详情
      $values['img_attr'] = json_encode($values['img_attr']);
      return $values;
    }


    /**
     * 获取品牌
     */
    private function getBrnadName($brand_id = '') {
        if(empty($brand_id)) return '';
        $find = TrdNewsBrandsTable::getInstance()->find($brand_id);
        if(empty($find)) return '';
        $find = $find->toArray();
        return $find['brand_name'];
    }

    protected function reziseImgPath($values, $path) {
        $img = $path . $values['img_path'];
        $thumbnail = substr($values['img_path'], 0, 7) . 'thumbnail-' . substr($values['img_path'], 7);
        $thumbnail480 = substr($values['img_path'], 0, 7) . 'thumbnail480-' . substr($values['img_path'], 7);
        //$bmiddle = substr($values['img_path'], 0, 7) . 'bmiddle-' . substr($values['img_path'], 7);

        //thumbnail缩略图
        $thumb_width = 165;
        $thumb_height = 155;
        $img1 = new Imagick($img);
        
        $data = $img1->getImageGeometry();
        $format = strtolower($img1->getImageFormat());
        if ($format == 'gif') {
            new imgResize($img, $path . $thumbnail, $thumb_width, $thumb_height, 1, 2);
            new imgResize($img, $path . $thumbnail480, 480, 480, 1, 2);
        } else {
            if ($data['height'] > $data['width']) {
                $img1->thumbnailImage(0, $thumb_height);
            } else {
                $img1->thumbnailImage($thumb_width, 0);
            }
            $img1->writeImages($path . $thumbnail, true);
            
            $img2 = new Imagick($img);
            $img2->thumbnailImage(480, 0);
            $img2->writeImages($path . $thumbnail480, true);
        }
        
        //bmiddle缩略图
//        $img2 = new Imagick($img);
//        $format = strtolower($img2->getImageFormat());
//        $bmiddle_width = $data['width'] > 450 ? 450 : $data['width'];
//        if ($format != 'gif') {
//            $img2->thumbnailImage($bmiddle_width, 0);
//            $img2->writeImages($path . $bmiddle, true);
//        }
    }


    /*
     * @return array $tagArray
     */

    private function getDefaultTagsChoice() {
        $tags = trdProductTagTable::getDefaultNormalUseTags();
        $tagArray = array();

        foreach ($tags as $tag) {
            $tagArray[$tag->getId()] = $tag->getName();
        }

        return $tagArray;
    }
	
    
    //生成缩略图
    private function thumb_img($file, $config) {
        $image = new Imagick($file);
        $image->thumbnailImage($config["width"], $config["height"]);
        $image->writeImages($config["target"], true);
    }
    
    public function bind(array $taintedValues = null, array $taintedFiles = null){
        if($taintedValues['root_id'])
        {
            $this->children_menus = TrdMenuTable::getInstance()->getChildrenMenu('',$taintedValues['root_id']);
            $menu_children_array = array();
            foreach($this->children_menus as $children) {
                $menu_children_array[$children->getId()] = $children->getName();
            }
            $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));
        }

        if($taintedValues['type']){ //国内商场的type为1，海淘为0
          //  echo $taintedValues['type'];
            $store_type =  $taintedValues['type'] == 1 ? 1 : 0;
            $store_array = array();
            $store_array[0] = '请选择所属商城';
            $storeObj = TrdStoreTable::getAllStoreByType($store_type);
            if ($storeObj){
                foreach ($storeObj as $store){
                    $store_array[$store->getId()] = $store->getName();
                }
            }
            $this->setWidget('store_id', new sfWidgetFormChoice(array('choices'=>$store_array)));//所属商城

            $root_type = $this->getOption("root_type");
           /* if($root_type == 1){
                $store_array_k = array_keys($store_array);
                array_shift($store_array_k);
                $this->setValidator('store_id', new sfValidatorChoice(
                        array('choices'=>$store_array_k,'required' => true),
                        array('invalid' => '请选择商城', 'required'=>'请选择商城'))
                );
            }else{*/
                $this->setValidator('store_id', new sfValidatorChoice(
                        array('choices'=>array_keys($store_array),'required' => false))
                );
            //}
        }


        return parent::bind($taintedValues,$taintedFiles);
    }

    /*
    *钩子匹配
    **/
    private function _pattern($value, $type = NUll){
        $pattern = '/\<img[^>]+class=\"trade_editor_test\"[^>]+title=\"([^>]+)\"[^>]+src=\"http:\/\/shihuo.hupucdn.com\/youhuiIndex\/201507\/3010\/ab48017fd3aea39b84d9df27ea13c51b\.png[^>]+\"\>/siU';
        $pattern_reverse    = '/\<widget_product.*title=\"(.*)\".*widget_product\>/siU';

        $compare = $pattern_arr = array();
        if($type == 'reverse'){
            preg_match_all($pattern_reverse, $value , $match);
            if(!empty($match[1])){
                foreach($match[1] as $k=>$v){
                    if(strpos($v, '_shihuoflag_') !== false){
                        $match_all = explode('_shihuoflag_', $v);//新版本以_shihuoflag_分隔
                    }else{
                        $match_all = explode('_f_', $v);
                    }
                    $match_title = $match_all[0];

                    $match_title = FunBase::base64ForQiniu($match_title);
                    $compare['<widget_product title="'.$v.'"></widget_product>']
                        = '<img class="trade_editor_test" title="'.$v.'" src="http://shihuo.hupucdn.com/youhuiIndex/201507/3010/ab48017fd3aea39b84d9df27ea13c51b.png?watermark/2/text/'.$match_title.'/font/5b6u6L2v6ZuF6buR/dx/150/dy/30/gravity/NorthWest">';
                }

                $value = strtr($value, $compare);
            }
        }else{
            preg_match_all($pattern, $value, $match);
            if(!empty($match[1])){
                foreach($match[1] as $k=>$v){
                    if(strpos($v, '_shihuoflag_') !== false){
                        $match_all = explode('_shihuoflag_', $v);//新版本以_shihuoflag_分隔
                    }else{
                        $match_all = explode('_f_', $v);
                    }
                    $match_title = $match_all[0];
                    $match_title = FunBase::base64ForQiniu($match_title);

                    $pattern_arr[]
                        = '/\<img[^>]+class=\"trade_editor_test\"[^>]+title="[^>]+"[^>]+src=\"http:\/\/shihuo.hupucdn.com\/youhuiIndex\/201507\/3010\/ab48017fd3aea39b84d9df27ea13c51b\.png\?watermark\/2\/text\/'.$match_title.'\/font[^>]+\"\>/siU';
                    $compare[]
                        = '<widget_product title="'.$v.'"></widget_product>';
                }

                if($compare){
                    $value =  preg_replace($pattern_arr, $compare, $value);
                }
            }
        }

        return $value;
    }
}
