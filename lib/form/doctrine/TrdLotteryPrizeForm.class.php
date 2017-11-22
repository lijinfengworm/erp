<?php

/**
 * TrdLotteryPrize form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdLotteryPrizeForm extends BaseTrdLotteryPrizeForm
{

    public static $_select_default = array(
        '1'=>'虚拟奖',
        '2'=>'实物奖',
        '3'=>'提示奖',
    );

    public static $_prize_type = array(
        '1'=>'第三方',
        '2'=>'站内',
    );

  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['lottery_id']);
      $this->disableLocalCSRFProtection();



      $this->setWidget('prize_name', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('prize_name',
          new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 64),
              array('required' => '名称必填！',  'max_length' => '不大于64个字')));


      $this->setWidget('prize_rand', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('prize_rand',
          new sfValidatorInteger(array('required' => true, 'trim' => true, ),
              array('required' => '中奖率必填！')));
      $this->setDefault('prize_rand', 0);


      $this->setWidget('is_virtual', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_select_default),array('class'=>'is_virtual radio')));
      $this->setValidator('is_virtual', new sfValidatorChoice(
          array('choices'=>array_keys(self::$_select_default)),array('required' => '必填')));
      $this->setDefault('is_virtual',2);



      $this->setWidget('virtual_type', new sfWidgetFormChoice(array('expanded' => true, "choices" => self::$_prize_type),array('class'=>'radio')));
      $this->setValidator('virtual_type', new sfValidatorChoice(
          array('choices'=>array_keys(self::$_prize_type)),array('required' => '必填')));



      $this->setWidget('prize_num', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator('prize_num',
          new sfValidatorInteger(array('required' => false, 'trim' => true, ),
              array('required' => '库存必填！')));
      $this->setDefault('prize_num',"");


      $this->widgetSchema->setLabels(array(
          'lottery_id' => '活动id',
          'prize_name' => '奖品名称',
          'prize_rand' => '中奖几率',
          'is_virtual' => '奖品类型',
          'virtual_type' => '奖品归属',
          'prize_num' => '库存',
          'listorder' => '排序',
          'prize_info' => '说明',
      ));

      $this->widgetSchema->setHelps(array(
          'prize_rand' => '<span style="color: red">不得大于最大中率 '.$this->getOption('max_rand').'</span>',
      ));
  }




    public function processValues($values) {
        $values = parent::processValues($values);
        if($this->getOption("lottery_id") && $this->isNew()) {
            $values['lottery_id'] = $this->getOption("lottery_id");
        }
        return $values;
    }





}
