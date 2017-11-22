<?php

/**
 * TrdProductAttr filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdProductAttrFormFilter extends BaseTrdProductAttrFormFilter
{
  public function configure()
  {

      $this->setWidget('show_brand',new sfWidgetFormFilterSelect(array('choices'=>array(0=>'有品牌',1=>'无品牌'))));
      $this->setValidator('show_brand', new sfValidatorPass(array('required' => false)));

      $this->setWidget('business',new sfWidgetFormFilterSelect(array('choices'=>array(
          null=>'请选择',
          '美国亚马逊'=>'美国亚马逊',
          '6pm'=>'6pm',
          'gnc'=>'gnc',
          'levis'=>'levis',
          'nbastore'=>'nbastore',
          '日本亚马逊'=>'日本亚马逊',
      ))));

  }

    /*显示品牌*/
    public function addShowBrandColumnQuery(Doctrine_Query $query, $field, $values)
    {
        $fieldName = $this->getFieldName($field);

        if (is_array($values) && isset($values['text']) && $values['text'])
        {
            $query->addWhere(sprintf('%s.%s = ?', $query->getRootAlias(), 'brand_id'),0);
        }else{
            $query->addWhere(sprintf('%s.%s != ?', $query->getRootAlias(), 'brand_id'),0);
        }
    }
}
