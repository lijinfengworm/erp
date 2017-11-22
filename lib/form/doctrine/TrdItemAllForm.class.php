<?php

/**
 * TrdItemAll form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdItemAllForm extends BaseTrdItemAllForm {

    public function configure() {
        
        unset($this["created_at"]);
        unset($this["updated_at"]);
        unset($this["give_money"]);
        unset($this["freight_payer"]);
        
        //商品默认信息
        $info = $this->getOption("info");
        
        //分类
        $menuTable   = TrdMenuTable::getInstance();
        $this->root_menus = $menuTable->getRootMenu(1,1);
        
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
            $this->children_menus = $menuTable->getChildrenMenu(1,$root_id);
            foreach($this->children_menus as $children) {
                $menu_children_array[$children->getId()] = $children->getName();
                $menu_children_value[$children->getId()] = $children->getId();
            }

        //id
        $this->setWidget("id", new sfWidgetFormInputHidden());
        $this->setValidator('id', new sfValidatorPass());

        //商品链接
        $this->setWidget("url", new sfWidgetFormInputHidden());

        //商品id
        $this->setWidget("item_id", new sfWidgetFormInputHidden());

        $this->setWidget("is_hide", new sfWidgetFormInputHidden());
        $this->setWidget("click_count", new sfWidgetFormInputHidden());
        $this->setWidget("like_count", new sfWidgetFormInputHidden());
        $this->setWidget('rank', new sfWidgetFormInputHidden());
        $this->setWidget('publish_date', new sfWidgetFormInputHidden());
        $this->setWidget('hupu_uid', new sfWidgetFormInputHidden());
        $this->setWidget('hupu_username', new sfWidgetFormInputHidden());

       
        //商品链接
        $this->setWidget("url", new sfWidgetFormInputHidden());

        //已售数量
        $this->setWidget("sold_count", new sfWidgetFormInputHidden());

        //价格
        $this->setWidget("price", new sfWidgetFormInput(array(), array("maxlength" => 9)));
        $this->setValidator('price',
            new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                    array('invalid' => '价格必须为数字，整数不超过6位，小数不超过2位，请修改。'))
                ),
                array('required' => true),
                array('required' => '价格不能为空')
            )
        );

        $this->setWidget("category_all_id", new sfWidgetFormInputHidden());

        //商品描述
        $this->setWidget('memo', new sfWidgetFormInputHidden());

        //店铺id
        $this->setWidget("shop_id", new sfWidgetFormInputHidden());
        
        //shoe id
        $this->setWidget("shoe_id", new sfWidgetFormInputHidden());
        
        //属性结合
        $this->setWidget("attr_collect",  new sfWidgetFormInputHidden());

         //名称
        $this->setWidget("title", new sfWidgetFormInput(array(), array("maxlength" => 60)));
        $this->setValidator('title', new sfValidatorAnd(array(
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-]{1,60}$|^[\._a-zA-Z0-9\s\-\x{4e00}-\x{9fa5}]{1,60}$/u'),
                array('invalid' => '商品名称不超过60个字符')),
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-\x{4e00}-\x{9fa5}]*$/u'),
                array('invalid' => '商品名称不包含特殊字符'))
            ),
            array('required' => true),
            array('required' => '商品名称不能为空')
        ));
        
        //副标题
        $this->setWidget("name", new sfWidgetFormInput(array(), array("maxlength" => 30)));

        $this->setValidator('name', new sfValidatorAnd(array(
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)]{1,30}$|^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]{1,30}$/u'),
                array('invalid' => '副标题不超过30个字符')),
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]*$/u'),
                array('invalid' => '副标题不包含特殊字符'))
            ),
            array('required' => true),
            array('required' => '副标题不能为空')
        ));

        $this->setWidget('mart', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));
        $this->setValidator('mart', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 10), array('required' => '商城必填', 'invalid' => '商城不能大于10个字')));

        //加入分类
        $this->setWidget("root_id", new sfWidgetFormChoice(array("choices" => $menu_root_array), array('onchange' => 'getSecondMenuById(this.value)')));
        $this->setValidator('root_id', new sfValidatorChoice(array('choices'=>$menu_root_value, 'required' => true), array('invalid' => '请选择一级分类', 'required'=>'请选择一级分类')));
        
        $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array), array('onchange' => 'getAttributeByChildrenId(this.value)')));
        $this->setValidator('children_id', new sfValidatorChoice(array('choices'=>$menu_children_value, 'required' => true), array('invalid' => '请选择二级分类', 'required'=>'请选择二级分类')));
        
        //图片
        $this->setWidget('img_url', new sfWidgetFormInputHidden());
        $this->setValidator('img_url',
            new sfValidatorAnd(
                new tradeImgValidator(
                    array('pattern' => '/^([0-9a-zA-Z\.\/]){1,200}$/u'),
                    array('invalid' => '图片不正确')
                ),
                array('required' => true),
                array('required' => '图片不能为空')
            ));

        $this->getWidgetSchema()->setNameFormat('data[%s]');
    }
    public function setup() {
        parent::setup();
//        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
//        $this->setWidget('shoe_id', new sfWidgetFormDoctrineJQueryAutocompleter(array(
//            'model' => 'trdItem',
//            'url' => url_for('trd_item/searchAjax'),
//        )));
    }     
    
    public function processValues($values) {
        $values = parent::processValues($values);
        $attr_collect = '';
        foreach ($values as $k=>$v){
            if (strpos($k,'attribute_') !== false && !empty($v)){
                $group_id = ltrim($k,'attribute_');
                $attr_collect .= 'G'.$group_id.'-A'.$v.',';
            }
        }
        $values['attr_collect'] = rtrim($attr_collect,',');
        $values['width'] = 208;
        $values['height'] = 117;
        $imageinfo = getimagesize('http://c'.mt_rand(1,2).'.hoopchina.com.cn'.$values['img_url']);
        if ($imageinfo){
            $values['width'] = $imageinfo[0];
            $values['height'] = $imageinfo[1];
        }
        return $values;
    }

    private function is_taobao($url) {
        $id = TaobaoUtil::parseItemId($url);
        if($id) {
            return true;
        } else {
            return false;
        }
    }
}
