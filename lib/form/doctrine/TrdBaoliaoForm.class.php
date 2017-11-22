<?php

/**
 * TrdBaoliao form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdBaoliaoForm extends BaseTrdBaoliaoForm
{
  private $separator = ',';
  public function configure() {
        
        unset($this["created_at"]);
        unset($this["updated_at"]);
        unset($this["external_username"]);
        unset($this["encrypt_url"]);
        unset($this["category_id"]);
        unset($this["give_money"]);
        unset($this["type"]);
        unset($this["shop_id"]);

        
        //商品默认信息
        $info = $this->getOption("info");
        //var_dump($this->getDefaults());exit;
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
        
        //属性结合
        $this->setWidget("attr_collect",  new sfWidgetFormInputHidden());

        
        //爆料人id和hupu name
        $this->setWidget("hupu_uid", new sfWidgetFormInputHidden());
        $this->setWidget("hupu_username", new sfWidgetFormInputHidden());
        
        //商品品牌和是否在发现好货中展示运动鞋
        $this->setValidator('brand',new sfValidatorString(array('required'=>false,'max_length'=>50),array('invalid' => '商品品牌不能超过50个字')));
        $this->setWidget('is_showsports', new sfWidgetFormChoice(array('choices' => array('0'=>'否', '1'=>'是'),'expanded' => true)));
        $this->validatorSchema['is_showsports'] = new sfValidatorAnd(array(
            $this->validatorSchema['is_showsports'],
            new sfValidatorChoice(array('choices' => array('0','1')))
            ));
        
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
        
       
        if($is_admin = $this->getOption("is_admin")) {
            //副标题
            $this->setWidget("sub_name", new sfWidgetFormInput(array(), array("maxlength" => 30)));

            $this->setValidator('sub_name', new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)]{1,30}$|^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]{1,30}$/u'),
                    array('invalid' => '副标题不超过30个字符')),
                new sfValidatorRegex(
                    array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]*$/u'),
                    array('invalid' => '副标题不包含特殊字符'))
                ),
                array('required' => false)
                //array('required' => '副标题不能为空')
            ));
            $res =  Doctrine_Core::getTable('TrdFindStore')->getMarts();
            $marts = array();
            $marts[''] = '请选择商城';
            if ($res) {
                foreach ($res as $mart) {
                    $marts[$mart->getName()] = $mart->getName();
                }
            }
            $this->setWidget('mart', new sfWidgetFormChoice(array('choices' =>$marts)));
            $this->setValidator('mart', new sfValidatorChoice(array( 'choices' => array_keys($marts))));
            
            
            //商品销售状态
            $this->setWidget('is_soldout', new sfWidgetFormChoice(array('choices'=>array('0'=>'正在进行','1'=>'售罄'))));
            $this->setValidator('is_soldout', new sfValidatorChoice(array('choices'=>array('0'=>'0','1'=>'1'))));
            
            $this->setWidget('publish_date', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20)));
            $this->setValidator('publish_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));  
            
            $this->setWidget("status",  new sfWidgetFormInputHidden());
            
        } else {
            unset($this["sold_count"]);
            unset($this["is_soldout"]);
            
            //商城
            $this->setWidget("mart",  new sfWidgetFormInputHidden());
        }    

        //商品描述
        $this->setWidget('memo', new sfWidgetFormInputHidden());
        $this->setValidator('memo',new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 280), array('required' => '商品简介不能为空',  'max_length' => '商品简介不大于140个字')));
        
        //名称
        $this->setWidget("name", new sfWidgetFormInput(array(), array("maxlength" => 60)));
        
        $this->setValidator('name', new sfValidatorAnd(array(
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)]{1,60}$|^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]{1,30}$/u'),
                array('invalid' => '名称不超过60个字符')),
            new sfValidatorRegex(
                array('pattern' => '/^[\._a-zA-Z0-9\s\-\(\)\x{4e00}-\x{9fa5}]*$/u'),
                array('invalid' => '名称不包含特殊字符'))
            ),
            array('required' => true),
            array('required' => '名称不能为空')
        ));
        
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
        if ($this->isNew()) {
            $values['publish_date'] = date('Y-m-d H:i:s',time());
            $values['status'] = 0;
        } else {
            $values['status'] = 2;
        }
        
        $values['encrypt_url'] = substr(md5($values['url']),0,8);
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
