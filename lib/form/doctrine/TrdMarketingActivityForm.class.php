<?php

/**
 * TrdMarketingActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdMarketingActivityForm extends BaseTrdMarketingActivityForm
{
    #　活动用户
    public static $useType = array(
        1=>'运营活动',
        2=>'推广活动',
    );
    # 活动类型
    public static $type = array(
        1=>'识货营销',
        2=>'平台营销',
        3=>'美亚自营',
    );
    # 活动模式
    public static $mode = array(
        1=>'识货减满金额',
        2=>'识货减满比例',
        3=>'识货折扣活动',
        4=>'美亚折扣活动',
    );

    #模式对应图标
    public static $modeToPic = array(
        1 => 'http://kaluli.hoopchina.com.cn/images/trade/haitao/discount-icon3.png?s=0912',
        2 => 'http://kaluli.hoopchina.com.cn/images/trade/haitao/discount-icon4.png?s=0912',
        3 => 'http://kaluli.hoopchina.com.cn/images/trade/haitao/discount-icon2.png?s=0912',
        4 => 'http://kaluli.hoopchina.com.cn/images/trade/haitao/discount-icon1.png?s=0912',
    );

    #模式对应2字缩写
    public static $modeToShortName = array(
        1 => '优惠',
        2 => '满折',
        3 => '折扣',
        4 => '打折',
    );

    # 下单备注
    public static $orderType = array(
        1=>'自动结算',
        2=>'折扣码结算',
    );

    # 范围
    public static $scope = array(
        1=>'全场活动',
        2=>'集合活动',
    );

    # 识货活动类型
    public static $shuhuoType = array(
        1,2,3
    );


    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);


        # 活动标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '标题必填',)));
        # 活动用途
        $this->setWidget('use_type', new sfWidgetFormChoice(array('choices'=>self::$useType)));
        $this->setValidator('use_type', new sfValidatorChoice(array('choices'=>array_keys(self::$useType),'required' => true)));//验证
        # 活动备注
        $this->setWidget('use_note', new sfWidgetFormInput(array(), array('size' => 20)));
        # 开始时间
        $this->setWidget('stime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('stime', new sfValidatorString(array('required' => true)));
        $this->setDefault('stime', date('Y-m-d H:i:s'));
        # 结束时间
        $this->setWidget('etime', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'maxlength' => 19, 'size' => 20)));
        $this->setValidator('etime', new sfValidatorString(array('required' => true)));
        $this->setDefault('etime', date('Y-m-d H:i:s'));
        # 展示缩写
        $this->setWidget('short_name', new sfWidgetFormInput(array(), array('size' => 50,)));
        $this->setValidator('short_name', new sfValidatorString(array('required' => true, 'trim' => true ), array('required' => '缩写必填',  'max_length' => '标题不大于6个字')));
        # 活动简介
        $this->setWidget('intro', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 10)));
        $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, ), array('required' => '标题必填',  'max_length' => '标题不大于10个字', 'min_length' => '标题不少于8个字')));
        # 活动类型
        $this->setWidget("type", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$type,'default'=>1),array('class'=>'type_status radio')));
        $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(self::$type), 'required' => true), array('invalid' => '请设置是活动类型', 'required'=>'请设置是活动类型')));
        # 活动模式
        $this->setWidget("mode", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$mode,'default'=>1),array('class'=>'mode_type radio')));
        $this->setValidator('mode', new sfValidatorChoice(array('choices'=>array_keys(self::$mode), 'required' => true), array('invalid' => '请设置是活动模式', 'required'=>'请设置是活动模式')));
        # 活动属性
        $this->setWidget('attr1', new sfWidgetFormInput(array(), array('size' => 10,'style'=>'display:none',)));
        $this->setWidget('attr2', new sfWidgetFormInput(array(), array('size' => 10,'style'=>'display:none',)));
        # 下单备注
        $this->setWidget("order_note", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$orderType,'default'=>1),array('class'=>'audit_status radio')));
        $this->setValidator('order_note', new sfValidatorChoice(array('choices'=>array_keys(self::$orderType), 'required' => true), array('invalid' => '请设置是下单备注', 'required'=>'请设置是下单备注')));
        # 商品范围
        $this->setWidget("scope", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$scope,'default'=>1),array('class'=>'scope radio')));
        $this->setValidator('scope', new sfValidatorChoice(array('choices'=>array_keys(self::$scope), 'required' => true), array('invalid' => '请设置是活动范围', 'required'=>'请设置是活动范围')));
        # 集合id
        $this->setWidget('group_id', new sfWidgetFormInput(array(), array('size' => 10)));

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
        elseif($values['mode'] == 3 || $values['mode'] == 4)
        {
            if( empty($values['attr2']) )
            {
                throw new sfValidatorError($validator, '折扣比例填写完整');
            }
            $values['attr1'] = '';
        }

        if($values['scope'] == 1  )
        {
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
            $group = TrdActivitySetTable::getInstance()->find($values['group_id']);
            if(empty($group))
            {
                throw new sfValidatorError($validator, '集合不存在');
            }
            if($group->status != 1)
            {
                throw new sfValidatorError($validator, '集合还没创建成功');
            }

        }
        if( $values['type'] == 1 )
        {
            $shihuo = self::$shuhuoType;
            if( !in_array($values['mode'],$shihuo))
            {
                throw new sfValidatorError($validator, '请选择识货类型的活动');
            }
            foreach($shihuo as $k=>$v)
            {
                if($values['mode'] == $v)
                {
                    unset($shihuo[$k]);
                    break;
                }
            }
//            $activitys = TrdMarketingActivityTable::getInstance()->createQuery()->whereIn('status',array(1,3))->whereIn('mode',$shihuo)->fetchArray();
//            foreach($activitys as $v)
//            {
//                if( ($values['stime'] >= $v['stime'] && $values['stime'] <= $v['etime']) || ($values['etime'] >= $v['stime'] && $values['etime'] <= $v['etime']) )
//                {
//                    throw new sfValidatorError($validator, "本活动时间和活动《{$v['title']}》日期有冲突！");
//                }
//            }
        }
        elseif( $values['type'] == 2 || $values['type'] == 3 )
        {
            if( $values['mode'] != 4 )
            {
                throw new sfValidatorError($validator, '请选择平台类型的活动');
            }
            # 排除其他活动时间冲突
            if($values['type'] == 2)
            {
                $in = 3;
            }
            else
            {
                $in = 2;
            }
//            $activitys = TrdMarketingActivityTable::getInstance()->createQuery()->whereIn('status',array(1,3))->andWhere('type = ?',$in)->fetchArray();
//            foreach($activitys as $v)
//            {
//                if( ($values['stime'] >= $v['stime'] && $values['stime'] <= $v['etime']) || ($values['etime'] >= $v['stime'] && $values['etime'] <= $v['etime']) )
//                {
//                    throw new sfValidatorError($validator, "本活动时间和活动《{$v['title']}》日期有冲突！");
//                }
//            }
        }



        if( $values['mode'] == 4 &&  empty($values['order_note']) )
        {
            throw new sfValidatorError($validator, '请选择下单备注');
        }


        return $values;
    }
}
