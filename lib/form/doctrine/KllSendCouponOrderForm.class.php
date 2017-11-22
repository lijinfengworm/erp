<?php

/**
 * KllSendCouponOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllSendCouponOrderForm extends BaseKllSendCouponOrderForm
{
    public static $_state=array(
        1=>'启动',
        2=>'关闭',
    );

    public static $_position = array(
        1=>'订单送券',
        2=>'微信活动'
    );

    const ZHIHUIYUNDONG = 1;
    const FIBO  = 2;
    const DF = 3;


  public function configure()
  {
        $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('title',
            new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 20),
                array('required' => '标题必填！',  'max_length' => '不大于20个字')));
        
        $this->setWidget('detail', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('detail',
            new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 50),
                array('required' => '描述必填！',  'max_length' => '不大于50个字')));
        
        $this->setWidget('s_time', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('s_time',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '必填！')));
        $this->setWidget('e_time', new sfWidgetFormInput(array(), array('class'=>'w460')));
        $this->setValidator('e_time',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '必填！')));
        
        $this->setWidget('record_id', new sfWidgetFormInput(array(), array('name'=>'kll_send_coupon_vip[record_id][]')));
        $this->setValidator('record_id',
            new sfValidatorString(array('required' => true, 'trim' => true),
                array('required' => '批次号错误！')));
        
        $this->setWidget('state', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_state),array('class'=>'lipinka_type radio')));
        $this->setValidator('state', new sfValidatorChoice(
            array('choices'=>array_keys(self::$_state)),array('required' => '必填')));


        $this->setWidget("position",new sfWidgetFormChoice(array('choices'=> self::$_position)));

        $this->setWidget("channel_id",new sfWidgetFormChoice(array("choices"=>self::getChannelByDictionary())));

        $this->setWidget('type', new sfWidgetFormInput(array(), array('class'=>'w220')));

  }

    public static  function getChannelByDictionary() {
        $serviceRequest  = new kaluliServiceClient();
        $serviceRequest->setVersion("1.0");
        $serviceRequest->setMethod("dictionary.get.dictionary");
        $serviceRequest->setApiParam("type",KllDictionaryForm::$couponChannelType);
        $serviceRequest->setApiParam("arrayType",1);
        $response = $serviceRequest->execute();
        if(!$response->hasError()) {
            $data =  $response->getData();
            return $data['data'];
        }

    }
}