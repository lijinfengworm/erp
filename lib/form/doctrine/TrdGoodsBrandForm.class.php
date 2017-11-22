<?php

/**
 * TrdGoodsBrand form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsBrandForm extends BaseTrdGoodsBrandForm
{
  public function configure()
  {
      unset($this['status']);
      $brand[0] = '新建一级';
      $brand['--'] = '----------';
      $tmp = TrdGoodsBrandTable::getInstance()->createQuery()->andWhere('pid = 0')->andWhere('status = 0')->fetchArray();
      foreach($tmp as $v)
      {
          $brand[$v['id']] = $v['name'];
      }

      $this->setWidget('pid', new sfWidgetFormChoice(array('choices'=>$brand)));
      $this->setValidator('pid', new sfValidatorChoice(array('choices'=>array_keys($brand),'required' => true)));//验证


      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '名称必填',)));
  }
}
