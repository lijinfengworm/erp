<?php

/**
 * KllItemTradelogTask form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllItemTradelogTaskForm extends BaseKllItemTradelogTaskForm
{
    public static $status = array(
        0=>'生成中',
        1=>'任务完成',
        2=>'已停止',
    );
  public function configure()
  {
      # 商品ID
      $this->setWidget('product_id', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('product_id', new sfValidatorString(array('required' => true, 'trim' => true,), array('required' => '商品ID必填')));

      # 商品数量
      $this->setWidget('total_num', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('total_num', new sfValidatorInteger(array('required' => true, 'trim' => true,), array('required' => '商品数量必填')));

      # 生成结束时间
      $this->setWidget('end_time', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setValidator('end_time', new sfValidatorString(array('required' => true)));
      $this->setDefault('end_time', date('Y-m-d H:i:s'));


      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values)
    {
        $item = KaluliItemTable::getInstance()->find($values['product_id']);
        if(empty($item))
        {
            throw new sfValidatorError($validator, '不存在的商品ID');
        }
        $values['end_time'] = strtotime($values['end_time']);
        if(empty($values['end_time']))
        {
            throw new sfValidatorError($validator, '截止生成时间必填');
        }
        $now = time();
        if($values['end_time']<$now)
        {
            throw new sfValidatorError($validator, '时间必须大于当前时间哦');
        }

        if(date("G",$values['end_time'])<8)
        {
            throw new sfValidatorError($validator, '截止时间必须大于8点');
        }

        $hour = date("G",$now);
        if($hour<8)
        {
            $now = strtotime(date('Y-m-d :08:00:00',$now));
        }
        $values['updated_time'] = $now;
        return $values;
    }
}
