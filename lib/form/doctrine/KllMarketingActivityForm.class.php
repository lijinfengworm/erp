<?php

/**
 * KllMarketingActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllMarketingActivityForm extends BaseKllMarketingActivityForm
{
    # 活动模式
    public static $mode = array(
        1=>'满减金额',
        2=>'买件数打折（2件以上）',
        3=>'单件折扣',
    );

    # 活动模式
    public static $short_mode = array(
        1=>'满减',
        2=>'折扣',
        3=>"折扣"
    );

    # 范围
    public static $scope = array(
        1=>'全场活动',
        2=>'集合活动',
    );

    # 是否显示抢购
    public static $type = array(
        0=>'否',
        1=>'是',
    );


  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);


      # 活动标题
      $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50)));
      $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '标题必填',)));

      # 开始时间
      $this->setWidget('stime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setValidator('stime', new sfValidatorString(array('required' => true)));
      $this->setDefault('stime', date('Y-m-d H:i:s'));
      # 结束时间
      $this->setWidget('etime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
      $this->setValidator('etime', new sfValidatorString(array('required' => true)));
      $this->setDefault('etime', date('Y-m-d H:i:s'));

      # 活动简介
      $this->setWidget('intro', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 10)));
      $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, ), array('required' => '简介必填',  'max_length' => '简介不大于10个字', 'min_length' => '简介太少了')));

      # 活动模式
      $this->setWidget("mode", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$mode,'default'=>3),array('class'=>'mode_type radio')));
      $this->setValidator('mode', new sfValidatorChoice(array('choices'=>array_keys(self::$mode), 'required' => true), array('invalid' => '请设置是活动模式', 'required'=>'请设置是活动模式')));
      # 活动属性
      $this->setWidget('attr1', new sfWidgetFormInput(array(), array('size' => 10,'style'=>'display:none',)));
      $this->setWidget('attr2', new sfWidgetFormInput(array(), array('size' => 10,'style'=>'display:none',)));
      $this->setWidget('attr3', new sfWidgetFormInput(array(), array('size' => 10,'style'=>'display:none',)));

      # 商品范围
      $this->setWidget("scope", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$scope,'default'=>1),array('class'=>'scope radio')));
      $this->setValidator('scope', new sfValidatorChoice(array('choices'=>array_keys(self::$scope), 'required' => true), array('invalid' => '请设置是活动范围', 'required'=>'请设置是活动范围')));
      # 集合id
      $this->setWidget('group_id', new sfWidgetFormInput(array(), array('size' => 10)));

      # 是否限时抢购
      $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>self::$type, 'default'=>0)));
      $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(self::$type),'required' => true)));//验证
 
 
      # 限时抢购人数
      $this->setWidget('type_limit', new sfWidgetFormInput(array(), array('size' => 10)));
      $this->setValidator('type_limit', new sfValidatorString(array('required' => false, 'trim' => true), array('required' => '必填',)));
 
      # 详情链接
      $this->setWidget('url', new sfWidgetFormInput(array(), array('size' => 100,'maxlength' => 255)));
      $this->setValidator('url', new sfValidatorUrl(array('required' => false, 'trim' => true), array('required' => '详情链接必填',)));
 
      $this->widgetSchema->setHelps(array(
          'intro' => '<span style="color: red">例如:满500减100，最多10个字</span>',

      ));


      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values)
    {
        $values['stime'] = strtotime($values['stime']);
        $values['etime'] = strtotime($values['etime']);

        if($values['stime']>=$values['etime'])
        {
            throw new sfValidatorError($validator, '开始时间不能大于等于结束时间');
        }

        if($values['mode'] == 1)
        {
            if( empty($values['attr1']) || empty($values['attr2']) )
            {
                throw new sfValidatorError($validator, '满减金额请填写完整');
            }
        }
        elseif($values['mode'] == 2)
        {
            if( empty($values['attr1']) || empty($values['attr2']) )
            {
                throw new sfValidatorError($validator, '满减比例请填写完整');
            }
        }


        if($values['scope'] == 1  )
        {
            if($values['type'] == 1)
            {
                throw new sfValidatorError($validator, '全场活动不支持限时抢购');
            }
            $values['group_id'] = 0;
        }
        if( $values['scope'] == 2  )
        {
            if( empty($values['group_id']) )
            {
                throw new sfValidatorError($validator, '请填写集合id');
            }

            if(!empty($values['id']) && !empty($values['group_id']) )
            {
                //  throw new sfValidatorError($validator, '不能修改集合id');
            }

            //todo 查询集合是否存在 状态是否已经生成
            $group = KllActivitySetTable::getInstance()->find($values['group_id']);
            if(empty($group))
            {
                throw new sfValidatorError($validator, '集合不存在');
            }
            if($group->status != 1)
            {
                throw new sfValidatorError($validator, '集合还没创建成功');
            }

        }
        # 全场活动不能有不同类型的活动
        if($values['scope'] == 1)
        {
            if($values['mode'] == 1)
            {
                $mode = 2;
            }
            elseif($values['mode'] == 2)
            {
                $mode = 1;
            }
            elseif($values['mode'] == 3){
                $mode = 2;
            }
            else
            {
                throw new sfValidatorError($validator, '活动类型出错');
            }
            if(!empty($mode))
            {
                $activitys = TrdMarketingActivityTable::getInstance()->createQuery()->whereIn('status',array(1,3))->andWhere('mode = ?',$mode)->andWhere('scope = ?',1)->fetchArray();
                foreach($activitys as $v)
                {
                    if( ($values['stime'] >= $v['stime'] && $values['stime'] <= $v['etime']) || ($values['etime'] >= $v['stime'] && $values['etime'] <= $v['etime']) )
                    {
                        throw new sfValidatorError($validator, "本活动时间和活动《{$v['title']}》日期有冲突！");
                    }
                }
            }
        }


        if( $values['mode'] == 4 &&  empty($values['order_note']) )
        {
            throw new sfValidatorError($validator, '请选择下单备注');
        }


        return $values;
    }
}
