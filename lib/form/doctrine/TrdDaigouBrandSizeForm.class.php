<?php

/**
 * TrdDaigouBrandSize form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdDaigouBrandSizeForm extends BaseTrdDaigouBrandSizeForm
{
    //状态
    private $_flag = array(
        1=>'展示',
        0=>'不展示',
    );


  public function configure()
  {
      unset($this['created_at']);
      unset($this['updated_at']);



    if($this->isNew()) {
        $this->setWidget('brand_id',new sfWidgetFormInputHidden());
        $this->setDefault('brand_id',$this->getOption('brand_id'));
    } else {
        unset($this['brand_id']);
    }

      $this->setWidget('content',new tradeWidgetFormUeditor(array('button_widget'=>true)));
      $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));

      $this->setWidget('status', new sfWidgetFormChoice(array('choices'=>$this->_flag),array('class'=>' select')));//类型
      $this->setValidator('status', new sfValidatorChoice( array('choices'=>array_keys($this->_flag)),array('required' => '状态必选')));


      $this->widgetSchema->setLabels(array(
          'title' => '尺码标题',
          'content' => '内容',
          'status' => '状态',
      ));
  }


    public function processValues($values) {
        $values = parent::processValues($values);
        $values['content'] = preg_replace("/<p>.*<\/p>/iU",'',$values['content']);
        $values['content'] = preg_replace("/<colgroup>.*<\/colgroup>/iU",'',$values['content']);
        $values['content'] = preg_replace("/<td\s*.*\s*>/iU","<td>",$values['content']);
        $values['content'] = preg_replace("/<tr\s*.*\s*>/iU","<tr>",$values['content']);
        $values['content'] = preg_replace("/<table\s*.*\s*>/iU","<table>",$values['content']);
        //echo '<textarea style="width:900px; height:600px;" >'.$values['content'].'</textarea>';
        //exit();
        return $values;
    }

}
