<?php

/**
 * trdGrouponTreasure filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trdGrouponTreasureFormFilter extends BasetrdGrouponTreasureFormFilter
{
   public function configure()
   {
       $this->setWidget('shop_name',new sfWidgetFormInput());
       $this->setValidator('shop_name', new sfValidatorPass(array('required' => false)));
   }

    public function addShopNameColumnQuery(Doctrine_Query $query, $field, $values)
    {
        $fieldName = $this->getFieldName($field);

        if (is_array($values) && isset($values['text']) && $values['text'])
        {
            $shop = trdBusinessmanTable::houtaGetList(0,20,array('shop_name'=> $values['text']));
            $hupu_id = null;
            foreach($shop as $shop_v){
                $hupu_id = $shop_v['hupu_uid'];
            }

            if($hupu_id){
                $query->andwhere(sprintf('%s.%s = ?', $query->getRootAlias(), 'hupu_uid'), $hupu_id);
            }

        }
    }
}
