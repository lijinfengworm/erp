<?php

/**
 * TrdOrder filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdOrderFormFilter extends BaseTrdOrderFormFilter
{
  public function configure()
  {
      $this->setWidget('business',new sfWidgetFormFilterSelect(array('choices'=>array(
          null=>'请选择',
          '美国亚马逊'=>'美国亚马逊',
          '6pm'=>'6pm',
          'gnc'=>'gnc',
          'levis'=>'levis',
          'nbastore'=>'nbastore',
          '日本亚马逊'=>'日本亚马逊',
          '香港仓库直发'=>'香港仓库直发',
          '识货上海仓库直发'=>'识货上海仓库直发',
          'ebay海外精选'=>'ebay海外精选',
      ))));
  }

    public function getFields()
    {
        $fields = parent::getFields();
        $fields['mart_order_number'] = 'Number';
        $fields['mart_express_number'] = 'Number';
        $fields['hupu_username'] = 'Number';
        return $fields;
    }

}
