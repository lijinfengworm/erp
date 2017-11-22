<?php

/**
 * TrdNews filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdNewsFormFilter extends BaseTrdNewsFormFilter
{
  public function configure()
  {
      $this->setWidget('show_daigou',new sfWidgetFormFilterSelect(array('choices'=>array(0=>'全部',1=>'代购'))));
      $this->setValidator('show_daigou', new sfValidatorPass(array('required' => false)));
  }

  /*是否显示代购*/
  public function addShowDaigouColumnQuery(Doctrine_Query $query, $field, $values)
  {
      $fieldName = $this->getFieldName($field);

      if (is_array($values) && isset($values['text']) && $values['text'])
      {
          $query->addWhere(sprintf('%s.%s != ?', $query->getRootAlias(), 'product_id'),0);
      }
  }
}
