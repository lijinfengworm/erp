<?php

/**
 * TrdGoodsCategory form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsCategoryForm extends BaseTrdGoodsCategoryForm
{
  public function configure()
  {
      unset($this['status']);
      $type[0] = '新建一级';
      $type['--'] = '----------';
      $tmp = TrdGoodsCategoryTable::getInstance()->createQuery()->andWhere('pid = 0')->andWhere('status = 0')->fetchArray();
      foreach($tmp as $v)
      {
          $type[$v['id']] = $v['name'];
      }

      $this->setWidget('pid', new sfWidgetFormChoice(array('choices'=>$type)));
      $this->setValidator('pid', new sfValidatorChoice(array('choices'=>array_keys($type),'required' => true)));//验证


      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '名称必填',)));
  }
}
