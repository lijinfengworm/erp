<?php

/**
 * KllHupuApicontent form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllHupuApicontentForm extends BaseKllHupuApicontentForm
{
  static $_type = [
      1=>"M站首页",
      4=> "虎扑sBBS"
    ];
  public function configure()
  {
    
    $this->setWidget('kll_hupu_title', new sfWidgetFormInput(array(), array('class'=>'w460')));
    $this->setValidator('kll_hupu_title',
        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 24),
            array('required' => '标题必填！',  'max_length' => '不大于24个字')));

    $this->setWidget('kll_hupu_subtitle', new sfWidgetFormInput(array(), array('class'=>'w460')));
    $this->setValidator("kll_hupu_subtitle",new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 14),
        array('required' => '副标题必填！',  'max_length' => '不大于14个字')));
    $this->setValidator("kll_hupu_url",new sfValidatorString(array('required' => true, 'trim' => true),
        array('required' => 'url必填！')));
    $this->setWidget('kll_hupu_type', new sfWidgetFormSelect(array('choices'=>self::$_type)));
    # 上传图片
    $rule = array(
        'required'=>true,
        'max_size'=>'500000',
      //    'height'=>80,
       //   'width'=>80,
        'path'=>'uploads/kaluli/item',
      //   'ratio'=>'1x1'
    );
    $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
    $this->setValidator("kll_hupu_time",new sfValidatorString(array('required' => true, 'trim' => true),
        array('required' => '发布时间必填！')));

    // $this->setValidator("kll_hupu_imgpath",new sfValidatorString(array('required' => true, 'trim' => true),
    //     array('required' => '图片必传！')));



    $this->setWidget("kll_hupu_origin",new sfWidgetFormSelect(array('choices'=>self::getOriginByDictionary())));


    
  }

  public static  function getOriginByDictionary() {
    $serviceRequest  = new kaluliServiceClient();
    $serviceRequest->setVersion("1.0");
    $serviceRequest->setMethod("dictionary.get.dictionary");
    $serviceRequest->setApiParam("type",KllDictionaryForm::$apiOriginType);
    $serviceRequest->setApiParam("arrayType",1);
    $response = $serviceRequest->execute();
    if(!$response->hasError()) {
      $data =  $response->getData();
      return $data['data'];
    }

  }
  
}
