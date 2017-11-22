<?php

/**
 * CpsUser form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CpsUserForm extends BaseCpsUserForm
{
  public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      //type
      $type_array = array(
          1=>'合作联盟',
          2=>'主播'
      );

      $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>$type_array)));//所属商城
      $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys($type_array),'required' => false)));//验证
  }
}
