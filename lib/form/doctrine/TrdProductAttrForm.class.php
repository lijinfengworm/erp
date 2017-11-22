<?php

/**
 * TrdProductAttr form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdProductAttrForm extends BaseTrdProductAttrForm
{
    private $_business = array(
        '美国亚马逊'=>'美国亚马逊',
        '6pm'=>'6pm',
        'gnc'=>'gnc',
        'levis'=>'levis',
        'nbastore'=>'nbastore',
        '日本亚马逊'=>'日本亚马逊',
        '香港仓库直发'=>'香港仓库直发',
        '识货上海仓库直发'=>'识货上海仓库直发',
        'ebay海外精选'=>'ebay海外精选',
    );
    private $_brands = array(''=>'请选择品牌');
    private $_status = array(
        0=>'正常',
        1=>'删除',
    );
    private $_purchase_status = array(
        0=>'审核通过',
        1=>'待审核'
    );

    private $_purchase_all_status = array(
        0=>'审核通过',
        1=>'待审核',
        2=>'退回重新编辑',
        3=>'被拒绝'
    );

  public function configure()
  {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['content']);
        unset($this['show_flag']);
        unset($this['crawl_flag']);
        unset($this['display']);
        unset($this['last_crawl_date']);
        unset($this['praise']);
        unset($this['dace_hits']);
        unset($this['dace_buy_hits']);
        unset($this['name']);
        unset($this['goods_id']);
        unset($this['shaiwu_count']);



       //如果不是新增  那么就移除发布人 和发布人名称
      if(!$this->isNew()) {
          unset($this['external_username']);
          unset($this['author_id']);
      }

        //默认信息
        $info = $this->getOption("info");
        //分类
        $menuTable   = TrdDaigouMenuTable::getInstance();
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
        $children_id = $this->getObject()->getChildrenId() ? $this->getObject()->getChildrenId() : 0;


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
       //品牌
       if($root_id && $children_id){
           $daigouBrand = TrdDaigouBrandTable::getBrand($root_id,$children_id);
           $brands_array = array();

           if($daigouBrand){
               $brands_ids = explode(',',$daigouBrand['brand_attr']);
               $brands_array = TrdNewsBrandsTable::getByIds($brands_ids);
               $brands_array = $this-> _sort($brands_ids,$brands_array);

           }

           foreach($brands_array as $b_k=>$b_v){
               $this->_brands[$b_v['id']] =  $b_v['brand_name'];
           }
       }

        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 12), array('required' => '标题必填',  'max_length' => '标题不大于20个字', 'min_length' => '标题不少于12个字')));

//
//        $this->setWidget('text', new sfWidgetFormCKEditor(array(),array()));
//        $this->setValidator('text', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));
//      
        $this->setWidget('url', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 1000)));
        $this->setWidget('business', new sfWidgetFormChoice(array("choices" => $this->_business)));
        $this->setWidget('start_date', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20,'value'=>$this->getObject()->getStartDate()?date('Y-m-d H:i',$this->getObject()->getStartDate()):'')));
        $this->setValidator('start_date', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
        $this->setWidget('end_date', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20,'value'=>$this->getObject()->getEndDate()?date('Y-m-d H:i',$this->getObject()->getEndDate()):'')));
        $this->setValidator('end_date', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
        $this->setValidator('url', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品url必填', 'invalid' => '商品url格式错误')));
        $this->setValidator('business', new sfValidatorChoice(array("choices" => $this->_business)));
        
        $this->setWidget('business_weight', new sfWidgetFormInput(array(), array( 'readonly'=>'readonly')));
        
        //状态
        $this->setWidget('status', new sfWidgetFormChoice(array("choices" => $this->_status)));
        $this->setValidator('status', new sfValidatorChoice(array("choices" => array_keys($this->_status))));


        //兼职发布状态 如果开启审核功能 那么就要移除审核form
        if($this->getOption('is_open_audit')) {
            unset($this['purchase_flag']);
            unset($this['purchase_msg']);
        } else if($this->getOption('is_audit_model')) {  //全职审核状态
            $auditMessage = $this->getOption("auditMessage");
            $this->setWidget('purchase_flag', new sfWidgetFormChoice(array('expanded' => true,"choices" => $this->_purchase_all_status),array('class'=>'audit_status radio')));
            $this->setValidator('purchase_flag', new sfValidatorChoice(array("choices" => array_keys($this->_purchase_all_status)),array('required' => '审核状态必填')));
            $this->setWidget('purchase_msg', new sfWidgetFormChoice(array(
                'choices'=>$auditMessage),array('class'=>' ')));//类型
            $this->setValidator('purchase_msg', new sfValidatorChoice(
                array('choices'=>array_keys($auditMessage)),array('required' => '拒绝信息必填')));
        } else { //default  默认状态
            unset($this['purchase_msg']);
            $this->setWidget('purchase_flag', new sfWidgetFormChoice(array("choices" => $this->_purchase_all_status)));
            $this->setValidator('purchase_flag', new sfValidatorChoice(array("choices" => array_keys($this->_purchase_all_status))));
        }



        //加入分类
        $token = FunBase::genRandomString(7);
        $this->setWidget("root_id", new sfWidgetFormChoice(array("choices" => $menu_root_array), array('data-id'=>$token,'class'=>'r_'.$token.'  root_id','onchange' => 'getSecondMenuById(this.value,"'.$token.'")')));
        $this->setValidator('root_id', new sfValidatorChoice(array('choices'=>$menu_root_value, 'required' => true), array('invalid' => '请选择分类', 'required'=>'请选择分类')));
        
        $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array),array('data-id'=>$token,'class'=>'c_'.$token.'  children_id')));
        $this->setWidget("exchange", new sfWidgetFormInput(array(), array("maxlength" => 9)));

      //品牌
      $this->setWidget('brand_id', new sfWidgetFormChoice(array("choices" => $this->_brands),array('data-id'=>$token,'class'=>'brand_id b_'.$token)));


      $this->setValidator('price',
            new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                    array('invalid' => '价格必须为数字，整数不超过6位，小数不超过2位，请修改。'))
                ),
                array('required' => true),
                array('required' => '人民币价格不能为空')
            )
        );

        $this->setValidator('exchange',
            new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                    array('invalid' => '价格必须为数字，整数不超过6位，小数不超过2位，请修改。'))
                ),
                array('required' => true),
                array('required' => '外币价格不能为空')
            )
        );

      $this->setValidator('original_cost',
          new sfValidatorAnd(array(
                  new sfValidatorRegex(
                      array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                      array('invalid' => '价格必须为数字，整数不超过6位，小数不超过2位，请修改。'))
              ),
              array('required' => false)
          )
      );

      $this->setValidator('discount',
          new sfValidatorAnd(array(
                  new sfValidatorNumber(array(),array('invalid'=>'折扣必须为整数')),
             ), array('required' => false)
          )
      );

        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            'height'=>400,
            'width'=>400,
            'path'=>'uploads/trade/haitao/cover'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        $this->setWidget('img_path', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('img_path', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));


        //简介
        $this->setWidget('intro', new sfWidgetFormTextarea(array(), array('cols' => 70, 'rows' => 13, 'onkeyup' => 'count()')));
        //内容
        $this->setWidget('memo',new tradeWidgetFormUeditor(array(),array('style'=>'height:100px;width:800px')));

      if($this->getOption('is_null_content') == 1) {
          $this->setValidator('intro', new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 2800), array()));
          $this->setValidator('memo', new sfValidatorString(array('required' => false, 'trim' => true), array()));
      } else {
          $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true,'min_length' => 20, 'max_length' => 2800), array('required' => '简介必填', 'max_length' => '简介不能大于2800个字')));
          $this->setValidator('memo', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));
      }

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

        $this->setWidget("limits", new sfWidgetFormInput(array(), array("maxlength" => 9)));
        $this->setValidator('limits', new sfValidatorInteger(array('required' => true), array('required' => '每人限购数量必填', 'invalid' => '每人限购数量必填')));
  
        $this->setWidget('news_id', new sfWidgetFormInputHidden());
        $this->setWidget('hits', new sfWidgetFormInputHidden());
        $this->setWidget('purchase_date', new sfWidgetFormInputHidden());
        $this->setWidget('start_date', new sfWidgetFormInputHidden());
        $this->setWidget('end_date', new sfWidgetFormInputHidden());
        $this->setWidget('comment_count_img', new sfWidgetFormInputHidden());
        $this->setWidget('comment_count', new sfWidgetFormInputHidden());
        $this->setWidget('tags_attr', new sfWidgetFormInputHidden());
        $this->setWidget('freight', new sfWidgetFormInputHidden());
        
        $this->setValidator('weight',
            new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                    array('invalid' => '重量必须为数字，整数不超过6位，小数不超过2位，请修改。'))
                ),
                array('required' => true),
                array('required' => '重量不能为空')
            )
        );
        $this->widgetSchema->setHelp('weight','<span style="color: red">必填项，优先级大于抓取重量。</span>');

         //推荐代购
        if (!empty($info) && $info->getIntro()) $this->setDefault('intro', $info->getIntro());
        if (!empty($info) && $info->getTitle()) $this->setDefault('title', $info->getTitle());
        if (!empty($info) && $info->getOrginalUrl()) $this->setDefault('url', $info->getOrginalUrl());
        if (!empty($info) && $info->getText()) $this->setDefault('memo', $info->getText());
        if (!empty($info) && $info->getRootId()) $this->setDefault('root_id', $info->getRootId());
        if (!empty($info) && $info->getChildrenId()) $this->setDefault('children_id', $info->getChildrenId());
        if (!empty($info) && $info->getBusiness()) $this->setDefault('business', $info->getBusiness());
        if (!empty($info) && $info->getNewsId()) $this->setDefault('news_id', $info->getNewsId());
        
        $this->setWidget('publish_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})", 'maxlength' => 19, 'size' => 20)));
        $this->setValidator('publish_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
        $this->setDefault('publish_date', date('Y-m-d H:i:s'));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
    }

    public function myCallback($validator, $values) {
        //商品库验证
        if($values['commodity_goods_id'] && $values['commodity_goods_name'] && !$values['commodity_desc']){
            $errorSchema = new sfValidatorErrorSchema($validator);
            $errorSchema->addError(new sfValidatorError($validator, '描述必填' ), 'commodity_desc');
            throw $errorSchema;
        }
        return $values;
    }

    /**
     * @param array $values
     * @return array
     */
    public function processValues($values) {
      $values = parent::processValues($values);
      $values['start_date'] = strtotime($values['start_date']);
      $values['end_date'] = strtotime($values['end_date']);
      // $values['discount_endtime'] = strtotime($values['discount_endtime']);

      if (sfContext::getInstance()->getConfiguration()->getApplication() == 'tradeadmin') {
          if ($this->isNew()) {
              $values['external_username'] = sfContext::getInstance()->getUser()->getTrdUsername();
              $values['author_id'] = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
          } else {
              $values['editor_id'] = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
          }
      } else {
          $values['external_username'] = sfContext::getInstance()->getUser()->getAttribute('username');
      }

      if ($this->isNew()){
          //热度
          $values['hits'] = mt_rand(100, 150);

          //判断是否开启审核
          if($this->getOption('is_open_audit')) {
              $values['purchase_flag'] = $this->getOption('audit_status');
          }
      }

        //如果是审核模式
        if($this->getOption("is_audit_model")) {
            if($values['purchase_flag'] == 3 || $values['purchase_flag'] == 2) {  //如果是拒绝
                $auditMessage = $this->getOption('auditMessage');
                if (!empty($values['purchase_msg'])) {
                    $values['purchase_msg'] = $auditMessage[$values['purchase_msg']];
                } else {
                    $_info = sfContext::getInstance()->getRequest()->getParameter('other_message');
                    if (!empty($_info)) {
                        $values['purchase_msg'] = $_info;
                    } else {
                        $values['purchase_msg'] = '未填写拒绝理由';
                    }
                }
            } else {
                $values['purchase_msg'] = '通过审核';
            }
            $values['purchase_uid'] = sfContext::getInstance()->getUser()->getTrdUserId();
            $values['purchase_date'] = time();
        }


        //如果是回退修改模式
        if($this->getOption("is_repair")) {
            $values['purchase_flag'] = 1;
            $values['purchase_msg'] = $values['purchase_uid'] = $values['purchase_date'] = '';
        }



        //判断审核时间
      if($values['purchase_date'] == 0 && $values['purchase_flag'] == 0){
          $values['purchase_date'] = time();
      } else if($values['purchase_flag'] == 1){
          $values['purchase_date'] = 0;
      }
      unset($values['upload_path']);

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

      return $values;
    }

    //处理品牌顺序
    private function  _sort($ids,$brands){
        array_multisort($ids,SORT_NUMERIC,$brands);
        return $brands;
    }
}
