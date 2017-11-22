<?php

/**
 * TrdMessage form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdMessageForm extends BaseTrdMessageForm
{
  public function configure()
  {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['is_delete']);
        unset($this['status']);
        
        $type = array('0'=>'全部','1'=>'android','2'=>'iphone','3'=>'wp');
        $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>$type)));//推送类型
        $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys($type))));//验证
        
  }
  public function processValues($values) {
      $values = parent::processValues($values);
      return $values;
  }
}
