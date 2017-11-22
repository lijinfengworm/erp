<?php

/**
 * TrdProduct form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdItemForm extends BaseTrdItemForm
{
    public function configure()
    {
        unset($this["created_at"]);
        unset($this["updated_at"]);

        //商品默认信息
        $info = $this->getOption("info");

        $brandTable   = TrdBrandTable::getInstance();
        $styleTable   = TrdStyleTable::getInstance();
        $colorTable   = TrdColorTable::getInstance();
        $catTable     = TrdCategoryTable::getInstance();
        $sizeTable    = TrdSizeTable::getInstance();

        $this->brands = $brandTable->getAll();
        $this->styles = $styleTable->getAll();
        $this->colors = $colorTable->getAll();
        $this->categories = $catTable->getAll();
        $this->sizes = $sizeTable->getAll();

        //id
        $this->setWidget("id", new sfWidgetFormInputHidden());
        $this->setValidator('id', new sfValidatorPass());
        $this->setWidget("is_verified", new sfWidgetFormInputHidden());
        $this->setWidget("is_hide", new sfWidgetFormInputHidden());
        $this->setWidget("click_count", new sfWidgetFormInputHidden());
        $this->setWidget("like_count", new sfWidgetFormInputHidden());
        $this->setWidget('rank', new sfWidgetFormInputHidden());
        $this->setWidget('publish_date', new sfWidgetFormInputHidden());
        $this->setWidget('hupu_uid', new sfWidgetFormInputHidden());
        $this->setWidget('hupu_username', new sfWidgetFormInputHidden());
        //品牌
        $brand_array = array();
        foreach($this->brands as $item) {
            $brand_array[$item->getId()] = $item->getName();
        }

        //商品链接
        $this->setWidget("url", new sfWidgetFormInputHidden());

        //已售数量
        $this->setWidget("sold_count", new sfWidgetFormInputHidden());
        //是否淘宝客
        $this->setWidget("give_money", new sfWidgetFormInputHidden());
        //是否包邮
        $this->setWidget("freight_payer", new sfWidgetFormInputHidden());

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
            //虎扑价格
        $this->setWidget("original_price", new sfWidgetFormInput(array(), array("maxlength" => 9)));
        $this->setValidator('original_price',
            new sfValidatorAnd(array(
                new sfValidatorRegex(
                    array('pattern' => '/^([0-9]){1,6}$|^([0-9]){1,6}\.[0-9]{0,2}$/'),
                    array('invalid' => '价格必须为数字，整数不超过6位，小数不超过2位，请修改。'))
                ),
                array('required' => FALSE)
            )
        );
        


        //商品id
        $this->setWidget("item_id", new sfWidgetFormInputHidden());


        //商品描述
        $this->setWidget('memo', new sfWidgetFormInputHidden());
        $this->setValidator('memo',new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 280), array('required' => '商品简介不能为空',  'max_length' => '商品简介不大于140个字')));
        

        //用户id
        //$this->setWidget("user_id", new sfWidgetFormInputHidden());

        //店铺id
        $this->setWidget("shop_id", new sfWidgetFormInputHidden());

        //品牌
        $this->setWidget("brand_id", new sfWidgetFormSelectRadio(array("choices" => $brand_array)));
        $this->setValidator('brand_id',
            new sfValidatorChoice(
                array('choices' => array_keys($brand_array), 'required' => true),
                array('invalid' => '品牌错误', 'required' => '品牌不能为空')
            )
        );


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

        //类别
        $category_array = array();
        foreach($this->categories as $item) {
            $category_array[$item->getId()] = $item->getName();
        }
        $this->setWidget("category_id", new sfWidgetFormSelectRadio(array("choices" => $category_array)));
        $this->setValidator('category_id',
            new sfValidatorChoice(
                array('choices' => array_keys($category_array), 'required' => true),
                array('invalid' => '类别错误', 'required' => '类别不能为空')
            )
        );


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

    private function is_taobao($url) {
        $id = TaobaoUtil::parseItemId($url);
        if($id) {
            return true;
        } else {
            return false;
        }
    }

}
