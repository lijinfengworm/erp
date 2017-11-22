<?php

/**
 * TrdGroupon form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGrouponForm extends BaseTrdGrouponForm {
    public static $types = array(
        0=>'普通团 500元/7天',
        1=>'mini团 350元/4天',
    );
    public static $is_ad = array(
        1=>'是',
        0=>'否',
    );

    public static $payType = array(
        0=>'余额',
        1=>'积分',
    );

    public function configure() {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['deleted_at']);
        unset($this['show_order']);
        unset($this['attr']);
        unset($this['attend_count']);
        unset($this['discount']);
        unset($this['praise']);
        
        //获取团购分类
        $category_array = array();
        $category_array[0] = '请选择分类';
        $categoryObj = TrdGrouponCategoryTable::getAllCategory();
        if ($categoryObj){
            foreach ($categoryObj as $category){
                $category_array[$category->getId()] = $category->getName();
            } 
        }


        $this->setWidget('memo', new sfWidgetFormCKEditor(array(), array()));
        $this->setValidator('memo', new sfValidatorString(array('required' => TRUE)));
        $this->setWidget('start_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('start_time', new sfValidatorDateTime(array('required' => TRUE, 'trim' => TRUE, 'datetime_output' => 'Y-m-d H:i:s')));
        $this->setDefault('start_time', date('Y-m-d H:i:s'));
        $this->setWidget('end_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('end_time', new sfValidatorDateTime(array('required' => TRUE, 'trim' => TRUE, 'datetime_output' => 'Y-m-d H:i:s')));
        $this->setDefault('end_time', date_format(date_add(date_create(), date_interval_create_from_date_string('10 days')), 'Y-m-d H:i:s'));
        $this->setWidget('intro', new sfWidgetFormTextarea(array(), array('cols' => 60, 'rows' => 6)));
        $this->setValidator('intro', new sfValidatorString(array('required' => false, 'trim' => true,'min_length' => 0, 'max_length' => 3000), array('max_length' => '简介不能大于3000个字符')));

        $this->setWidget('category_id', new sfWidgetFormChoice(array('choices'=>$category_array)));//所属分类
        $this->setValidator('category_id', new sfValidatorChoice(array('choices'=>array_keys($category_array),'required' => false)));//验证
        
        $taobaourl = sfContext::getInstance()->getRequest()->getParameter('taobaourl');

        $this->setWidget('url', new sfWidgetFormInput());
        if (!empty($taobaourl)) {
            $taobao = new TaobaoUtil();
            $itemId = $taobao->getItemIdByUrl($taobaourl);
            if (!empty($itemId)) {
                $this->setDefault('item_id', $itemId);
                $this->widgetSchema['item_id']->setAttribute('value', $itemId);
            }
            $itemInfo = $taobao->getItemInfo($itemId);
            if (!empty($itemInfo['detail_url'])) {
                $this->setDefault('url', $itemInfo['detail_url']);
                $this->widgetSchema['url']->setAttribute('value', $itemInfo['detail_url']);
            }
            if (!empty($itemInfo['nick'])) {
                $shop = TrdShopTable::getInstance()->getShopByNickName($itemInfo['nick']);
                $this->setDefault('shop_id', $shop->getId());
                $this->widgetSchema['shop_id']->setAttribute('value', $shop->getId());
            }
        }


        #封面图
        $_one_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_frist', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_frist_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_frist",data.url);',"rule"=>$_one_rule)));
        $this->setValidator('images_frist', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));
        $this->setDefault('images_frist',$this->getObject()->getImagesFrist());




        #封面图
        $_two_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_second', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_second_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_second",data.url);',"rule"=>$_two_rule)));
        $this->setValidator('images_second', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));
        $this->setDefault('images_second',$this->getObject()->getImagesSecond());


        #封面图
        $_three_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_third', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_third_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_third",data.url);',"rule"=>$_three_rule)));
        $this->setValidator('images_third', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));
        $this->setDefault('images_third',$this->getObject()->getImagesThird());



        #封面图
        $_four_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_fourth', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_fourth_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_fourth",data.url);',"rule"=>$_four_rule)));
        $this->setValidator('images_fourth', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '封面图片不能为空')));
        $this->setDefault('images_fourth',$this->getObject()->getImagesFourth());


        #资质证书
        $_certifications_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_certifications', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_certifications_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_certifications",data.url);',"rule"=>$_certifications_rule)));
        $this->setValidator('images_certifications', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '资质证书不能为空')));
        $this->setDefault('images_certifications',$this->getObject()->getImagesCertifications());



        #尺码t图
        $_prosize_rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'5000000',
            'min_width'=>100,
            'min_height'=>100
        );
        $this->setWidget('images_certifications', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('images_certifications_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_groupon_images_prosize",data.url);',"rule"=>$_prosize_rule)));
        $this->setValidator('images_certifications', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '尺码图片不能为空')));
        $this->setDefault('images_certifications',$this->getObject()->getImagesProsize());

        $this->widgetSchema->setLabels(array(
            'images_frist_btn' =>'上传第一张图片',
            'images_second_btn' =>'上传第二张图片',
            'images_third_btn' =>'上传第三张图片',
            'images_fourth_btn' =>'上传第四张图片',
        ));


    /*
        $this->setWidget('images_frist', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/uploads' . $this->getObject()->upload_images_dir . $this->getObject()->getImagesFrist(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImagesFrist(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('images_frist', new sfValidatorFile(array(
            'validated_file_class' => 'dateDirValidatedFile',
            'required' => !$this->getObject()->getImagesFrist(),
            'max_size' => 5000000, 'path' => sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir ,
            'mime_types' => 'web_images'), array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M'
        )));

        $this->setWidget('images_second', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/uploads' . $this->getObject()->upload_images_dir . $this->getObject()->getImagesSecond(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImagesSecond(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('images_second', new sfValidatorFile(array(
            'validated_file_class' => 'dateDirValidatedFile',
            'required' => !$this->getObject()->getImagesSecond(),
            'max_size' => 5000000, 'path' => sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir,
            'mime_types' => 'web_images'), array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M'
        )));

        $this->setWidget('images_second', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/uploads' . $this->getObject()->upload_images_dir . $this->getObject()->getImagesSecond(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImagesSecond(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('images_second', new sfValidatorFile(array(
            'validated_file_class' => 'dateDirValidatedFile',
            'required' => !$this->getObject()->getImagesSecond(),
            'max_size' => 5000000, 'path' => sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir,
            'mime_types' => 'web_images'), array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M'
        )));

        $this->setWidget('images_third', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/uploads' . $this->getObject()->upload_images_dir . $this->getObject()->getImagesThird(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImagesThird(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('images_third', new sfValidatorFile(array(
            'validated_file_class' => 'dateDirValidatedFile',
            'required' => !$this->getObject()->getImagesThird(),
            'max_size' => 5000000, 'path' => sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir,
            'mime_types' => 'web_images'), array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M'
        )));

        $this->setWidget('images_fourth', new sfWidgetFormInputFileEditable(array(
            'file_src' => '/uploads' . $this->getObject()->upload_images_dir . $this->getObject()->getImagesFourth(),
            'is_image' => TRUE,
            'edit_mode' => $this->getObject()->getImagesFourth(),
            'template' => '<div>%input%%file%</div>'
        )));
        $this->setValidator('images_fourth', new sfValidatorFile(array(
            'validated_file_class' => 'dateDirValidatedFile',
            'required' => !$this->getObject()->getImagesFourth(),
            'max_size' => 5000000, 'path' => sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir,
            'mime_types' => 'web_images'), array('mime_types' => '图片格式不正确', 'max_size' => '图片尺寸最大5M'
        )));
    */
    }

    public function processValues($values) {

        $values = parent::processValues($values);
        /*
        if (isset($values['images_frist']) && is_object($values['images_frist'])) {
            $haschange['images_frist'] = 1;
        }
        if (isset($values['images_second']) && is_object($values['images_second'])) {
            $haschange['images_second'] = 1;
        }
        if (isset($values['images_third']) && is_object($values['images_third'])) {
            $haschange['images_third'] = 1;
        }
        if (isset($values['images_fourth']) && is_object($values['images_fourth'])) {
            $haschange['images_fourth'] = 1;
        }




        //处理图片
        if (isset($values['images_frist']) && isset($haschange['images_frist'])) {
            $img = sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir . $values['images_frist'];
            $i = new Imagick($img);
            $i->thumbnailImage(500, 300);
            $i->writeImages($img, true);
        }
        if (isset($values['images_second']) && isset($haschange['images_second'])) {
            $img = sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir . $values['images_second'];
            $i = new Imagick($img);
            $i->thumbnailImage(500, 300);
            $i->writeImages($img, true);
        }
        if (isset($values['images_third']) && isset($haschange['images_third'])) {
            $img = sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir . $values['images_third'];
            $i = new Imagick($img);
            $i->thumbnailImage(500, 300);
            $i->writeImages($img, true);
        }
        if (isset($values['images_fourth']) && isset($haschange['images_fourth'])) {
            $img = sfConfig::get('sf_upload_dir') . $this->getObject()->upload_images_dir . $values['images_fourth'];
            $i = new Imagick($img);
            $i->thumbnailImage(500, 300);
            $i->writeImages($img, true);
        }*/

        return $values;
    }

}
