<?php

/**
 * TrdSeoNews form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdSeoNewsForm extends BaseTrdSeoNewsForm
{
    private $separator = ',';
    private $exp_sina = '/(https{0,1}:?\/\/)?t.cn\/\w+/is';

    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);


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
        $store_type = $this->getObject()->getType() ? ($this->getObject()->getType() == 1 ? false : 0) : false;     //运动鞋为找出全部商城，海淘为国外
        $store_array = array();
        $store_array[0] = '请选择所属商城';
        $storeObj = TrdStoreTable::getAllStoreByType($store_type);
        if ($storeObj){
            foreach ($storeObj as $store){
                $store_array[$store->getId()] = $store->getName();
            }
        }
        //从表数据
        $info = '';
        if($id = $this->getObject()->getId()){
            $info = trdSeoNewsClusterTable::getInstance()->findOneby('id',$id);
        }

        $this->widgetSchema->setHelps(array(
            'intro' => '<span style="color: red">简介必填，长度不能大于200个字符，发布必须要有一张图片</span>',
            'orginal_type' => '<span style="color: red">跳转网站url、跳转网站必填</span>',
            'tags_attr'=>'<span style="color: red">为了识货的未来，请填写最精准tag，多个tag中间用(,)</span>',
            'img_path'=>'<span style="color: red">图片大小为400x400</span>',
        ));
        $this->setWidget('title', new sfWidgetFormInput(array(), array('placeholder'=>'','class'=>'calibration w460','size' => 50, 'maxlength' => 100)));
        $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>array('0'=>'请选择类型','1'=>'运动鞋','2'=>'海淘专区')),array('onchange' => 'getStoreByType(this.value)')));//商品类型

        //品牌text
        $this->setWidget('brand_text', new sfWidgetFormInputText(
            array('default'=>$this->getBrnadName($this->getObject()->getBrandId())),
            array('id'=>'news_brand_id','autocomplete'=>'off')));

        $this->setValidator('brand_text', new sfValidatorString(
            array('required' => true),array('required' => '品牌必填!')));


        //品牌
        $this->setWidget('brand_id', new sfWidgetFormInputHidden(array()));
        $this->setValidator('brand_id', new sfValidatorString(
            array('required' => false)));

        $this->setWidget('store_id', new sfWidgetFormChoice(array('choices'=>$store_array)));//所属商城
        $this->setValidator('store_id', new sfValidatorChoice(array('choices'=>array_keys($store_array),'required' => false)));//验证


        //$this->setValidator('direct_words', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20), array('required' => '商品图片链接文字必填',  'max_length' => '商品图片链接文字不大于20个字')));
        $this->setValidator('type', new sfValidatorChoice(array('choices'=>array('0'=>'','1'=>'1','2'=>'2')), array('required' => '类型必填','invalid' => '类型必填')));

        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 1), array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于12个字')));

        $this->widgetSchema->setHelp('title','<span style="color: red">标题必填，长度在1-40个字符</span>');

        $this->setWidget('intro', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 6, 'onkeyup' => 'count()')));
        $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true,'min_length' => 50, 'max_length' => 500), array('required' => '简介必填', 'max_length' => '简介不能大于500个字')));
        $this->widgetSchema->setHelp('intro','<span style="color: red">简介必填，长度不能小于50个字，而其不能大于500个字符，发布必须要有一张图片</span>');

        $this->setWidget('text',new tradeWidgetFormUeditor(array('button_widget'=>true)));
        $this->setValidator('text', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));


        $this->setWidget('orginal_url', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 1000)));
        $this->setWidget('orginal_type', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));
        $this->setWidget('product_id', new sfWidgetFormInput(array(), array('size' => 10, 'maxlength' => 20)));

        $this->setWidget('tags_attr', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 1000)));
        $this->setValidator('tags_attr', new sfValidatorPass());

        $this->setWidget('publish_date', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));



        /*
        $this->setWidget('img_path', new sfWidgetFormInputFileEditable(array(
            'file_src' => $this->getObject()->getImgPath(),
            'is_image' => true,
            'edit_mode' => $this->getObject()->getImgPath(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('img_path', new sfValidatorFile(array(
            'validated_file_class' => 'dateValidatedFile',
            'required' => false, 'max_size' => 5000000,
            'path' => sfConfig::get('sf_upload_dir') . '/trade/news/' . date('ymd') . '/',
            'mime_types' => 'web_images'),array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M')));
        */
        #封面图
        $rule = array(
            'required'=>true,
            'path'=>'newsIndex',
            'max_size'=>'500000',
            'min_width'=>200,
            'min_height'=>200
        );
        $this->setWidget('img_path', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('img_path_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_seo_news_img_path",data.url);',"rule"=>$rule)));
        $this->setValidator('img_path', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));










        $this->setValidator('publish_date', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
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
        $this->setWidget('reply_count', new sfWidgetFormInputHidden());
        $this->setWidget('support', new sfWidgetFormInputHidden());
        $this->setWidget('against', new sfWidgetFormInputHidden());


        //从表
        if (!empty($info) && $info->getIntro()) $this->setDefault('intro', $info->getIntro());
        if (!empty($info) && $info->getText()) $this->setDefault('text', $info->getText());


        //加入分类
        $this->setWidget("root_id", new sfWidgetFormChoice(array("choices" => $menu_root_array), array('onchange' => 'getSecondMenuById(this.value)')));
        $this->setValidator('root_id', new sfValidatorChoice(array('choices'=>$menu_root_value, 'required' => true), array('invalid' => '请选择分类', 'required'=>'请选择分类')));

        $this->setWidget("children_id", new sfWidgetFormChoice(array("choices" => $menu_children_array)));

        //文章类型
        $this->setWidget("root_type", new sfWidgetFormChoice(array("choices" => array(0=>'普通',1=>'精品',2=>'专题',3=>'教程',4=>'晒单'))));

        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
        $this->setDefault('publish_date', date('Y-m-d H:i:s'));
    }

    public function myCallback($validator, $values) {


        if (empty($values['root_id'])){
            throw new sfValidatorError($validator, '请选择分类');
        } else if (empty($values['children_id'])){
            $menuTable   = TrdMenuTable::getInstance();
            $childrencount = $menuTable->getChildrenMenuCount('',$values['root_id']);
            if ($childrencount){
                throw new sfValidatorError($validator, '请把分类补充完整');
            }
        }
        //判断品牌
        $values['brand_id'] = TrdNewsBrandsTable::getInstance()->createBrand(trim($values['brand_text']));
        return $values;
    }

    public function processValues($values) {


        //tags
        if(isset($values['tags_attr'])){
            $values['tags_attr'] = str_replace('，',',', $values['tags_attr']);

            if($values['tags_attr']){
                $tags = explode(',',$values['tags_attr']);
                foreach($tags as $key=>$val){
                    $tagsProdutct = TrdProductTagTable::getInstance()->findOneBy('name', $val);
                    if(!$tagsProdutct){
                        $tagsProdutct = new trdProductTag();
                        $tagsProdutct->setName($val);
                        $tagsProdutct->save();
                    }
                }
            }
        }

        //判断发布人
        if(sfContext::getInstance()->getConfiguration()->getApplication() == 'backend') {
            $uid = sfContext::getInstance()->getUser()->getAttribute('uid');
        } else if (sfContext::getInstance()->getConfiguration()->getApplication() == 'tradeadmin') {
            $uid = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        } else {
            $uid = '-'.sfContext::getInstance()->getUser()->getAttribute('uid');
        }
        if ($this->isNew()) {
            $values['author_id'] = $uid;
        }
        $values['editor_id'] = $uid;

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

        if($taintedValues['type']){  //运动鞋为找出全部商城，海淘为国外
            //  echo $taintedValues['type'];
            $store_type =  $taintedValues['type'] == 1 ? false : 0;
            $store_array = array();
            $store_array[0] = '请选择所属商城';
            $storeObj = TrdStoreTable::getAllStoreByType($store_type);
            if ($storeObj){
                foreach ($storeObj as $store){
                    $store_array[$store->getId()] = $store->getName();
                }
            }
            $this->setWidget('store_id', new sfWidgetFormChoice(array('choices'=>$store_array)));//所属商城
            $this->setValidator('store_id', new sfValidatorChoice(array('choices'=>array_keys($store_array),'required' => false)));//验证
        }
        return parent::bind($taintedValues,$taintedFiles);
    }
}
