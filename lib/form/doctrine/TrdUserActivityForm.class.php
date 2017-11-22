<?php

/**
 * TrdUserActivity form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdUserActivityForm extends BaseTrdUserActivityForm
{
    private $default_type = 1;
    public function configure()
    {
        sfConfig::set('sf_web_debug', true);
        unset($this['updated_at']);
        unset($this['created_at']);
        //类型
        $type_array = TrdUserActivity::$type;
       // FunBase::myDebug(array_keys($type_array));
        $this->setWidget("type", new sfWidgetFormChoice(array("choices" => $type_array,"default"=>$this->default_type)));
        $this->setValidator('type', new sfValidatorNumber(
                array('required' => true))
        );

        //礼品集合
        $this->setWidget("attr", new sfWidgetFormInputHidden());
        $this->setValidator('attr', new sfValidatorString(
                array( 'required' => false))
        );

        //状态
        $this->setWidget("status", new sfWidgetFormChoice(array('choices' => TrdUserActivity::$status)));
        $this->setValidator('status', new sfValidatorChoice(
                array('choices'=>array('0'=>0,1=>1),'required' => true))
        );

        //开始时间
        $this->setWidget('start_time', new sfWidgetFormInput(array(), array('class'=>'J_date',
            'onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})",
            'maxlength' => 19,
            'size' => 20))
        );
        $this->setValidator('start_time', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i')));

        //结束时间
        $this->setWidget('end_time', new sfWidgetFormInput(array(), array('class'=>'J_date',
            'onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})",
            'maxlength' => 19,
            'size' => 20)));
        $this->setValidator('end_time', new sfValidatorDateTime(array('required' => true, 'trim' => true, 'datetime_output' => 'Y-m-d H:i')));

        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
    }

    public function myCallback($validator, $values){

        if ($values['type']){
            $values['attr'] = serialize(TrdUserActivity::$attr[$values['type']]);
        }

        //验证活动类型唯一进行
        if($values['start_time'] && $values['end_time']){
            $id = $values['id'] ? $values['id'] : false;

            $isActivityStart = trdUserActivityTable::isHasActivity($values['type'], $values['start_time'], $id, false);
            $isActivityEnd = trdUserActivityTable::isHasActivity($values['type'], $values['end_time'], $id, false);

            if($isActivityStart){
                $errorSchema = new sfValidatorErrorSchema($validator);
                $errorSchema->addError(new sfValidatorError($validator, '同时间段类不能有类型相同的活动' ), 'start_time');
                throw $errorSchema;
            }
            if($isActivityEnd){
                $errorSchema = new sfValidatorErrorSchema($validator);
                $errorSchema->addError(new sfValidatorError($validator, '同时间段类不能有类型相同的活动' ), 'end_time');
                throw $errorSchema;
            }
        }

        //验证
        return $values;
    }
}
