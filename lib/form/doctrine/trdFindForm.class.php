<?php

/**
 * trdFind form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trdFindForm extends BasetrdFindForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['author_id']);
      unset($this['audit_id']);
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
      $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));
      $this->setValidator('root_id', new sfValidatorChoice(array('choices'=>$menu_root_value, 'required' => true), array('invalid' => '请选择分类', 'required'=>'请选择分类')));

      #商城
      $storeObj = TrdStoreTable::getAllStoreByType(1);//国内商城
      if ($storeObj){
          foreach ($storeObj as $store){
              $store_array[$store->getId()] = $store->getName();
          }
      }
      $this->setWidget('store_id', new sfWidgetFormChoice(array('choices'=>$store_array)));//所属商城
      $this->setValidator('store_id', new sfValidatorChoice(
              array('choices'=>array_keys($store_array),'required' => true)
          )
      );
      #商城END

      #title
      $this->setWidget('title', new sfWidgetFormInput(array(),array('class'=>'calibration w340')));
      $this->setValidator('title', new sfValidatorString(
          array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 12),
          array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于12个字'))
      );
      #title end

      #源地址
      $this->setWidget('orginal_url', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 1000)));
      $this->setWidget('orginal_type', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));
      $this->setValidator('orginal_url', new sfValidatorUrl(
          array('required' => true, 'trim' => true),
          array('required' => '跳转网站url必填', 'invalid' => '跳转网站url格式错误'))
      );
      $this->setValidator('orginal_type', new sfValidatorString(
          array('required' => true, 'trim' => true, 'max_length' => 20),
          array('required' => '跳转网站必填', 'invalid' => '跳转网站不能大于20个字'))
      );
      #源地址end

      #发布时间
      $this->setWidget('publish_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setDefault('publish_date', date('Y-m-d H:i:s'));
      $this->setValidator('publish_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      #发布时间end

      #状态
      $status_array = TrdFind::$_status;
      $this->setWidget("status", new sfWidgetFormChoice(array("choices" => $status_array), array()));
      #状态end

      #图片
      $this->setWidget('imgs_attr',new sfWidgetFormInputText());
      $this->setValidator('imgs_attr', new sfValidatorPass());
      #图片end

      #tag
      $this->setWidget('tags_attr',new sfWidgetFormInputText());
      $this->setValidator('tags_attr', new sfValidatorPass());
      #tag end

      #价格
      $this->setWidget('price',new sfWidgetFormInput(array(), array('class'=>'w80','size' => 50, 'maxlength' => 1000)));
      $this->setValidator('price', new sfValidatorString(
              array('required' => true, 'trim' => true),
              array('required' => '价格必填'))
      );
      #价格end

      #内容
      $this->setWidget('text',new sfWidgetFormTextarea(array(), array('class'=>'find_text','cols'=>"100", 'rows'=>"5", 'placeholder'=>"20字到160字之间")));
      $this->setValidator('text', new sfValidatorString(array(
          'required' => true, 'trim' => true, 'max_length'=>160, 'min_length'=>20
      ), array(
          'required' => '推荐理由必填',
          'max_length'=> '不得超过160字',
          'min_length'=>'不得小于20字',
      )));
      #内容end

      $this->setWidget('hits', new sfWidgetFormInputHidden(array()));
      $this->setWidget('reply_count', new sfWidgetFormInputHidden(array()));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

   public function myCallback($validator, $values) {
        if($values['status'] != 2 ){
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
        //图片
       if($values['imgs_attr'] && count($values['imgs_attr']) <= 4){
           $values['imgs_attr'] = json_encode($values['imgs_attr']);
       }else{
           throw new sfValidatorError($validator, '图片必须上传张且小于等于四张');
       }

       //tag
       $tagsLen = 0;
       if($values['tags_attr']){
           if(count($values['tags_attr']['name']) >= 3){
               foreach($values['tags_attr']['name'] as $tags_attr_k=>$tags_attr_v){
                   $tagsLen += strlen($tags_attr_v);
                   if($tags_attr_k >= 2)break;
               }
               if($tagsLen>36){
                   throw new sfValidatorError($validator, '前三个标签字数总和不大于12个汉字.');
               }
           }
           $values['tags_attr'] = json_encode($values['tags_attr']);
       }else{
           throw new sfValidatorError($validator, '标签必须有');
       }

        //判断发布人
        if (sfContext::getInstance()->getConfiguration()->getApplication() == 'tradeadmin') {
            $uid = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        } else {
            $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
        }

        if ($this->isNew()) {
            $values['author_id'] = $uid;
            $values['audit_id']  = $uid;
        }else{
            $values['audit_id']  = $uid;
        }

        return $values;
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
