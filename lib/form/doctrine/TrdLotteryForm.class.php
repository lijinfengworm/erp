<?php

/**
 * TrdLottery form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdLotteryForm extends BaseTrdLotteryForm
{


    public static $_select_default = array(
        '1'=>'是',
        '2'=>'否',
    );

    public static $_status = array(
        '1'=>'正常',
        '2'=>'下线',
        '3'=>'删除',
    );


    public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      $this->disableLocalCSRFProtection();

      $this->setWidget('lottery_name', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('lottery_name',
          new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 64),
              array('required' => '名称必填！',  'max_length' => '不大于64个字')));

      $this->setWidget('lottery_desc', new sfWidgetFormTextarea(array(), array('class'=>'w640','onkeyup' => 'count()')));
      $this->setValidator('lottery_desc',
          new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 5000),
              array(  'max_length' => '不大于5000个字')));

      $this->setWidget('max_rand', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('max_rand',
          new sfValidatorInteger(array('required' => true, 'trim' => true, 'min' => 100),
              array('required' => '中奖率必填！',  'min' => '不得小于100')));
        $this->setDefault('max_rand', 100);

      $this->setWidget('is_must', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_select_default),array('class'=>'radio')));
      $this->setValidator('is_must', new sfValidatorChoice(
          array('choices'=>array_keys(self::$_select_default)),array('required' => '必填')));

      $this->setWidget('fail_msg', new sfWidgetFormInput(array(), array('class'=>'w640')));
      $this->setValidator('fail_msg', new sfValidatorString(array('required' => false, 'trim' => true, ), array()));


      $this->setWidget('lottery_num_type', new sfWidgetFormChoice(array('expanded' => true, "choices" => array(1=>'每日')),array('class'=>'radio')));
      $this->setValidator('lottery_num_type', new sfValidatorChoice(
          array('choices'=>array(0=>1)),array('required' => '必填')));


      $this->setWidget('user_lottery_num', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('user_lottery_num', new sfValidatorString(array('required' => false, 'trim' => true, ), array()));

      $this->setWidget('attr_lottery_num', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('attr_lottery_num', new sfValidatorString(array('required' => false, 'trim' => true, ), array()));


        $this->setWidget('start_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('start_time', new sfValidatorString(array('required' => true, 'trim' => true, ), array('required' => '必填')));
        $this->setDefault('start_time', date('Y-m-d H:i:s',time()));


        $this->setWidget('end_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('end_time', new sfValidatorString(array('required' => true, 'trim' => true, ), array('required' => '必填')));

        $this->setDefault('end_time', date('Y-m-d H:i:s',time()+(86400*7)));



        $this->setWidget('status', new sfWidgetFormChoice(array( "choices" => self::$_status)));
        $this->setValidator('status', new sfValidatorChoice(
            array('choices'=>array_keys(self::$_status)),array('required' => '必填')));


        $rule = array(
            'required'=>true,
            'path'=>'lottery',
        );
        $this->setWidget('bg_img', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('bg_img_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_lottery_bg_img",data.url);',"rule"=>$rule)));
        $this->setValidator('bg_img', new sfValidatorString(array('required' => false, 'trim' => true)));



        $this->setWidget('pointer_img', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('pointer_img_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_lottery_pointer_img",data.url);',"rule"=>$rule)));
        $this->setValidator('pointer_img', new sfValidatorString(array('required' => false, 'trim' => true)));



        $this->setWidget('round_img', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
        $this->setWidget('round_img_btn',new tradeWidgetFormKupload(array("callback"=>'callback("trd_lottery_round_img",data.url);',"rule"=>$rule)));
        $this->setValidator('round_img', new sfValidatorString(array('required' => false, 'trim' => true)));


        $this->widgetSchema->setLabels(array(
          'lottery_name' => '活动名称',
          'lottery_keys' => '关键字',
          'lottery_desc' => '描述',
          'max_rand' => '最大中奖率',
          'is_must' => '必中',
          'fail_msg' => '未中提示',
          'lottery_num_type' => '抽奖类型',
          'user_lottery_num' => '最大抽奖次',
          'attr_lottery_num' => '附加抽奖次',
          'start_time' => '开始时间',
          'end_time' => '结束时间',
          'status' => '状态',
          'bg_img' => '大背景图',
          'pointer_img' => '指针图',
          'round_img' => '转盘图',
          'bg_img_btn' => '上传背景图',
          'pointer_img_btn' => '上传指针图',
          'round_img_btn' => '上传转盘图',
      ));


        $this->widgetSchema->setHelps(array(
            'max_rand' => '<span style="color: red">最大中奖率不得低于100，一般填写10000</span>',
            'bg_img' => '默认不填，就是这张图啦  <a target="_blank" href="'.TrdLottery::$BG_IMG.'">点击查看</a>',
            'pointer_img' => '默认不填，就是这张图 <a target="_blank" href="'.TrdLottery::$POINTER_IMG.'">点击查看</a>',
            'round_img' => '默认不填，就是这张图 <a target="_blank" href="'.TrdLottery::$ROUND_IMG.'">点击查看</a>',
        ));




  }



    public function processValues($values) {
        $values = parent::processValues($values);
        if(!empty($values['start_time'])) $values['start_time'] = strtotime($values['start_time']);
        if(!empty($values['end_time'])) $values['end_time'] = strtotime($values['end_time']);
        return $values;
    }


}
