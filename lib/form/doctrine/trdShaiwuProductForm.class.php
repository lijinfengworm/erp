<?php

/**
 * trdShaiwuProduct form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trdShaiwuProductForm extends BasetrdShaiwuProductForm
{
  private $separator = ',';
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['source']);
      unset($this['activity_id']);
      unset($this['rank']);

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
       //加入分类
      $this->setWidget("root_id", new sfWidgetFormChoice(array("choices" => $menu_root_array), array('onchange' => 'getSecondMenuById(this.value)')));
      //$this->setValidator('root_id', new sfValidatorChoice(array('choices'=>$menu_root_value, 'required' => true), array('invalid' => '请选择分类', 'required'=>'请选择分类')));
      $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));


      /*
      #品牌text
      $this->setWidget('brand_text', new sfWidgetFormInputText(
          array('default'=>$this->getBrnadName($this->getObject()->getBrandId())),
          array('id'=>'news_brand_id','autocomplete'=>'off')));
      #品牌
      $this->setWidget('brand_id', new sfWidgetFormInputHidden(array()));
      #品牌end
      */

      /**
       * 品牌  + 型号  + tag   多级联动
       */
      if($this->isNew()) {
          $this->setWidget('brand_id', new sfWidgetFormChoice(array("choices" => array('0' => '请选择品牌')), array()));
          #型号
          $this->setWidget('model',new  sfWidgetFormChoice(array("choices" => array()), array()));
      } else {
          //通过栏目 获取品牌
          $brandData = TrdAttributeTable::getByChildrenIdToBrandData($this->getObject()->getChildrenId());
          $this->setWidget('brand_id', new sfWidgetFormChoice(array("choices" => $brandData), array()));
          $this->setDefault('brand_id',$this->getObject()->getBrandId());

          //型号 通过品牌获取型号
          $modelData = TrdAttributeTable::getByBrandToModelData($this->getObject()->getBrandId());
          $this->setWidget('model',new  sfWidgetFormChoice(array("choices" =>$modelData), array()));
          $this->setDefault('brand_id',$this->getObject()->getModel());
      }
      //获取标签
      $this->setWidget('tag_ids',new sfWidgetFormInputHidden());


      /*
      #标签
      $tags_names = array();
      if($tag_ids = $this->getObject()->getTagIds()){
          $tag_ids_arr = explode(',',$tag_ids);
          foreach($tag_ids_arr as $k=>$v){
              $tags = trdTagsTable::getInstance()->find($v);
              if($tags)$tags_names[] = $tags['name'];
          }
      }
      $this->setWidget('tag_ids',new sfWidgetFormInput());

      $this->setWidget('tagList',new sfWidgetFormInput(array(),array('class'=>' w340')));
      $this->setDefault('tagList',join(',',$tags_names));
      $this->widgetSchema->setHelp('tagList','标签以,分隔');
      #标签end
      */









      #title
      $this->setWidget('title', new sfWidgetFormInput(array(),array('class'=>'calibration w340')));
      #title end

      //是否精品
      $is_hot_array = TrdShaiwuProduct::$_isHot;
      $this->setWidget("is_hot", new sfWidgetFormChoice(array("choices" => $is_hot_array)));


      #状态
      $status_array = TrdShaiwuProduct::$_status;
      $this->setWidget("status", new sfWidgetFormChoice(array("choices" => $status_array), array('onchange' => 'showStatusReason(this.value)')));
      $this->setWidget("status_reason", new sfWidgetFormTextarea(array(), array('cols' => '90')));
      #状态end

      #文章类型
      $type_arr =  TrdShaiwuProduct::$_type;
      $this->setWidget("type", new sfWidgetFormChoice(array("choices" => $type_arr)));
      #文章类型end

      #金币
      $this->widgetSchema->setHelp('gold','普通范围 200~500,精品范围 1000~1500');

      $this->setWidget("gold", new sfWidgetFormInput(array()));
      #金币end

      #内容
      $this->setWidget('content',new tradeWidgetFormUeditor());
      #从表数据
      $info = '';
      if($id = $this->getObject()->getId()){
          $info = trdShaiwuProductContentTable::getInstance()->findOneby('product_id',$id);
      }
      if (!empty($info) && $info->getContent()) $this->setDefault('content', $info->getcontent());


      # 链接urls
      if(!empty($info->urls))
      {
          $urls = json_decode($info->urls,true);

          if(is_array($urls))foreach($urls as $k=>$url)
          {
              $this->setWidget("urls[{$k}]", new sfWidgetFormInput(array()));
              $this->setDefault("urls[{$k}]", $url);
          }
      }


      #内容end



      #作者ID
      if($author_id = $this->getObject()->getAuthorId()){
          $this->setWidget('author_id',new sfWidgetFormInput(array(),array('readonly '=>'readonly')));
      }else{
          $this->setWidget('author_id',new sfWidgetFormInput(array()));
      }
      //$this->setValidator('author_id', new sfValidatorInteger(array('required' => true, 'trim' => true), array('required' => '作者必填')));
      #作者ID end



      #发布时间
      $this->setWidget('publish_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setDefault('publish_time', date('Y-m-d H:i:s'));
      #发布时间end

      #封面图
      $rule = array(
          'required'=>true,
          'path'=>'shaiwu',
          'max_size'=>'500000',
          'width'=>400,
          'height'=>400
      );
      $this->setWidget('front_pic', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
      $this->setWidget('front_pic_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_shaiwu_product_front_pic",data.url);',"rule"=>$rule)));
      #封面图end

      $this->setWidget('support', new sfWidgetFormInputHidden(array()));
      $this->setWidget('agaist', new sfWidgetFormInputHidden(array()));
      $this->setWidget('hits', new sfWidgetFormInputHidden(array()));
      $this->setWidget('comment_count', new sfWidgetFormInputHidden(array()));
      $this->setWidget('author_name', new sfWidgetFormInputHidden(array()));
      $this->setWidget('intro', new sfWidgetFormInputHidden(array()));


      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }


    public function processValues($values) {

        //tags
        if(isset($values['tagList'])){
            $tagList = array();
            if(!empty($values['tagList'])){
               $tagList =  str_replace('，',',',$values['tagList']);
               $tagList = explode(',',$tagList);
            }

            $tags_ids = array();
            foreach($tagList as $k=>$v){
                $tags = trdTagsTable::getInstance()->byName($v);
                if(!$tags){
                    $tags = new trdTags();
                    $tags->setName($v);
                    $tags->save();
                }

                $tags_ids[] = $tags['id'];
            }
            //$values['tag_ids'] = join(',',$tags_ids);
        }

        return $values;
    }

    public function myCallback($validator, $values) {




        if($values['status'] != 2 ){
            if (($this->isNew() || $this->getOption('isConvert')) && is_null($values['front_pic']) && !$values['front_pic']) {
                throw new sfValidatorError($validator, '封面图必须存在');
            }
            if (empty($values['root_id'])){
                throw new sfValidatorError($validator, '请选择分类');
            } else if (empty($values['children_id'])){
                $menuTable   = TrdMenuTable::getInstance();
                $childrencount = $menuTable->getChildrenMenuCount('',$values['root_id']);
                if ($childrencount){
                    throw new sfValidatorError($validator, '请把分类补充完整');
                }
            }
        }


        //判断发布人
        if(sfContext::getInstance()->getConfiguration()->getApplication() == 'backend') {
            $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
            $username = sfContext::getInstance()->getUser()->getAttribute('username');
        } else if (sfContext::getInstance()->getConfiguration()->getApplication() == 'tradeadmin') {
            $uid = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
            $username = sfContext::getInstance()->getUser()->getTrdUsername();
        } else {
            $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
            $username = sfContext::getInstance()->getUser()->getAttribute('username');
        }

        if ($this->isNew()) {
            $values['author_id'] = $uid;
            $values['author_name'] = $username;
        }


        //判断品牌
        //$values['brand_id'] = TrdNewsBrandsTable::getInstance()->createBrand(trim($values['brand_text']));

        //简介
        if(isset($values['content']) && !empty($values['content'])){
            $values['intro'] =  FunBase::getsubstrutf8(strip_tags($values['content']),0, 300);
        }
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


    public function bind(array $taintedValues = null, array $taintedFiles = null){
        if(isset($taintedValues['root_id']))
        {
            $this->children_menus = TrdMenuTable::getInstance()->getChildrenMenu('',$taintedValues['root_id']);
            $menu_children_array = array();
            foreach($this->children_menus as $children) {
                $menu_children_array[$children->getId()] = $children->getName();
            }
            $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));
        }
        return parent::bind($taintedValues,$taintedFiles);
    }
}
