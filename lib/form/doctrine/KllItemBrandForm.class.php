<?php

/**
 * KllItemBrand form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllItemBrandForm extends BaseKllItemBrandForm
{


  public function configure()
  {
    $this->setWidget('name', new sfWidgetFormInput(array(), array('class'=>'w220')));
    $this->setValidator('name',
        new sfValidatorString(array('required' => true, 'trim' => true),
            array('required' => '品牌名称必填！')));

    $this->setWidget('weight', new sfWidgetFormInput(array(), array('class'=>'w220')));
    $this->setValidator('weight',
        new sfValidatorString(array('required' => false, 'trim' => true),
            array()));

    # 品牌logo
    $logoRule = array(
        'required'=>true,
        'max_size'=>'500000',
 //         'height'=>192,
   //       'width'=>192,
        'path'=>'uploads/kaluli/item',
      //   'ratio'=>'1x1'
    );
    $this->setWidget('upload_path_logo',new tradeWidgetFormKupload(array("callback"=>"displayImageLogo(data.url);","rule"=>$logoRule)));
    #品牌banner
    $bannerRule = array(
        'required'=>true,
        'max_size'=>'500000',
        'height'=>200,
        'width'=>500,
        'path'=>'uploads/kaluli/item',
      //   'ratio'=>'1x1'
    );

    $this->setWidget('upload_path_banner',new tradeWidgetFormKupload(array("callback"=>"displayImageBanner(data.url);","rule"=>$bannerRule)));

    $this->setWidget("place",new sfWidgetFormInput(array(), array('class'=>'w220')));
    $this->setValidator('place',
        new sfValidatorString(array('required' => false, 'trim' => true),
            array()));

    $this->setWidget("place_en",new sfWidgetFormInput(array(), array('class'=>'w220')));
    $this->setValidator('place_en',
        new sfValidatorString(array('required' => false, 'trim' => true),
            array()));

    $flagRule = array(
        'required'=>true,
        'max_size'=>'500000',
        'height'=>64,
        'width'=>96,
        'path'=>'uploads/kaluli/item',
      //   'ratio'=>'1x1'
    );
    $this->setWidget('upload_path_flag',new tradeWidgetFormKupload(array("callback"=>"displayImageFlag(data.url);","rule"=>$flagRule)));

    $this->setWidget('description', new sfWidgetFormTextarea());
    $this->setValidator('description',
        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 500),
            array('required' => '描述必填！',  'max_length' => '不大于500个字')));
    $this->setValidator("logo",new sfValidatorString(array("required" => true),array("required"=>"logo必传")));
    $this->setValidator("banner", new sfValidatorString(array("required"=>true),array("required"=>"banner必传")));
    $this->setValidator("place_flag",new sfValidatorString(array("required"=>true),array("required"=>"产地国旗图必传")));

    //设置规范

    $this->widgetSchema->setHelps(array(
        'logo' => '<span class="c-999">图片大小192px*192px</span>',
        'banner'  => '<span class="c-999">图片大小500px*200px</span>',
        'place_flag'=>'<span class="c-999">图片大小96px*64px</span>'

    ));
  }

  /**
   * 品牌按照权重排序功能方法
   * @param $info
   */
  public static function sortBrand($info) {
      if(empty($info)) {
        return array();
      }
      $brands = KllItemBrandTable::getInstance()->createQuery()->where("status = 1")->fetchArray();
      //重组品牌数组
      $brands = KaluliFun::my_sort($brands,"weight",SORT_DESC);
      $sortInfo = array();
      foreach($brands as $k=>$v) {
          foreach($info as $ik => $iv) {
                if($v['name'] == $iv) {
                  $sortInfo[] = $iv;
                }
          }
      }

      return $sortInfo;
  }

}
