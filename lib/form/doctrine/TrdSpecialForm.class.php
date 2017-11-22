<?php

/**
 * TrdSpecial form.
 * 识货专题 form
 */
class TrdSpecialForm extends BaseTrdSpecialForm {

    //模板
    private $_template = array(
        1=>"模板1",
        2=>"模板2",
        4=>"模板4",
        5=>"模板5",
        6=>"模板6",
        7=>"模板7",
        8=>"模板8"
    );

    //状态
    private $_flag = array(
        1=>'不展示',
        2=>'展示',
        3=>'定时发布',
    );

    //状态
    private $_show_journal = array(
        0=>'不展示',
        1=>'展示',
    );


    //期刊分类
    private $_journal_type = array(
      1=>'球鞋',
      2=>'评测',
      3=>'潮流',
      4=>'生活',
    );


  public function configure() {
      unset($this['created_at']);
      unset($this['updated_at']);
      unset($this['deleted_at']);
      unset($this['support']);
      unset($this['agaist']);
      unset($this['comment_count']);
      $type = $this->getOption('type');
      if(empty($type)) unset($this['info']);

      //判断是否在修改
      $is_edit = $this->getOption('edit');
      if(!empty($is_edit)) {
          //修改专题 不允许修改专题模板 所以要删除模板字段
          unset($this['template']);
      }
      /* 专题名称 */
      $this->setWidget('name', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator("name",  new sfValidatorString(array('max_length' => 100, 'required' => true),array('required' => '标题必填')));

      /* M站专题名称 */
      $this->setWidget('m_title', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator("m_title",  new sfValidatorString(array('max_length' => 80, 'required' => false)));


      /* 期刊分类  */
      $this->setWidget('journal_type_id', new sfWidgetFormChoice(array("choices" => $this->_journal_type),array('class'=>'')));


      /* 期刊标题名称 */
      $this->setWidget('journal_title', new sfWidgetFormInput(array(), array('class'=>'w180')));
      $this->setValidator("journal_title",  new sfValidatorString(array('max_length' => 80, 'required' => false)));

      /* 期刊简介 */
      $this->setWidget('journal_desc', new sfWidgetFormTextarea(array(), array( 'text-limit'=>'500',  'tip-id'=>'journal_desc_limit' ,'class'=>'gwyy_textarea h80 w560  J_text_limit')));
      $_journal_desc_length = 0;
      if(!empty($_POST['trd_special']['journal_desc'])) {
          $_journal_desc_length = strlen($_POST['trd_special']['journal_desc']);
      }
      $this->setValidator("journal_desc",  new sfValidatorString(array('max_length' => 500, 'required' => false),array('max_length'=>'字数太多，你输入了 '.$_journal_desc_length.' 个字。最多只能输入 500 个字！')));

      /*  期刊头图  */
      $top_image_rule = array(
          'required'=>true,
          'path'=>"special",
          'max_size'=>'500000',
          'height'=>300,
          'width'=>300
      );
      $this->setWidget('journal_img', new sfWidgetFormInput(array(), array('class'=>'w240 J_date')));
      $this->setWidget('journal_img_btn',new tradeWidgetFormKupload(array("callback"=>'journal_callback("trd_special_journal_img",data.url);',"rule"=>$top_image_rule)));
      $this->setValidator('journal_img', new sfValidatorString(array('required' => false, 'trim' => true), array('required' => '封面图片不能为空')));


      /* 期刊 是否加入期刊  */
      $this->setWidget('show_journal', new sfWidgetFormChoice(array('expanded' => true, "choices" => $this->_show_journal),array('class'=>'audit_status radio')));
      $tpl_id = $this->getOption('tpl_id');
      if(in_array($tpl_id,TrdSpecial::$NOT_DATA_TMP)) {
          $this->setDefault('show_journal',1);
      } else {
          $this->setDefault('show_journal',0);
      }
      $this->setValidator('show_journal', new sfValidatorChoice(array('choices'=>array_keys($this->_show_journal),'required' => false)));//验证


          //支持数
          $this->setWidget('support', new sfWidgetFormInput(array(), array('class'=>'w40')));
          $this->setValidator("support",  new sfValidatorString(array('max_length' => 100, 'required' => false)));

          //反对数
          $this->setWidget('agaist', new sfWidgetFormInput(array(), array('class'=>'w40')));
          $this->setValidator("agaist",  new sfValidatorString(array('max_length' => 100, 'required' => false)));

          //查看数
          $this->setWidget('click_count', new sfWidgetFormInput(array(), array('class'=>'w40')));
          $this->setValidator("click_count",  new sfValidatorString(array('max_length' => 100, 'required' => false)));



      /* 定期发布  */
      $this->setWidget('timing_interval', new sfWidgetFormInput(array(), array('class'=>'J_date','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'%y-%M-%d'})",'maxlength' => 19, 'size' => 20)));
      /*  设置定时时间  */
      $this->setValidator('timing_interval', new sfValidatorDateTime(array('required' => false, 'trim' => true, 'datetime_output' => 'Y-m-d H:i:s')));
      $this->setDefault('timing_interval','');
      if(!$this->isNew()) {
          $_timingInterval = $this->getObject()->getTimingInterval();
          if(!empty($_timingInterval)) {
              $this->getObject()->setTimingInterval(date('Y-m-d H:i:s', $this->getObject()->getTimingInterval()));
          } else {
              $this->getObject()->setTimingInterval('');
          }
      }


      $this->widgetSchema->setHelps(array(
          'timing_interval'=>'如果要定时发布，就填写具体的时间，否则记得留空！',
      ));

      /* 专题备注 */
      $this->setWidget('remarks', new sfWidgetFormInput(array(), array('class'=>'w340')));
      $this->setValidator("remarks",  new sfValidatorString(array('max_length' => 100, 'required' => false)));

      /*  所属分类  */
      $this->setWidget('cateid', new sfWidgetFormChoice(array( "choices" => TrdSpecialCateTable::getUndelCate()),array()));

      /* 专题模板 */
       $this->setWidget('template', new sfWidgetFormChoice(array( "choices" => $this->_template)));
       $this->setWidget('special_status', new sfWidgetFormChoice(array( "choices" => $this->_flag)));
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {

        if($values['show_journal']) {
            if(empty($values['journal_title'])) throw new sfValidatorError($validator, '请填写期刊名！');
            if(empty($values['journal_img'])) throw new sfValidatorError($validator, '请填写期刊图片！');
            if(empty($values['journal_desc'])) throw new sfValidatorError($validator, '请填写期刊简介！');
        }
        return $values;
    }





    public function processValues($values) {
        if($this->isNew()) {
            if(empty($values['support'])) $values['support'] = rand(10,35);
            if(empty($values['agaist'])) $values['agaist'] = rand(0,6);
            if(empty($values['click_count'])) $values['click_count'] = rand(2,20);
        }

        //判断是不是定时发布
        if(!empty($values['timing_interval']) && strtotime($values['timing_interval']) !== false  && time() < strtotime($values['timing_interval'])) {
            $values['special_status'] = TrdSPecialTable::$TIMING_FLAG;
            $values['timing_interval'] = strtotime($values['timing_interval']);
        }

        return $values;
    }


}
