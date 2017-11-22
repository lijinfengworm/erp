<?php

/**
 * transferPlayer filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class transferPlayerFormFilter extends BasetransferPlayerFormFilter
{
  public function configure()
  {
        $this->setWidget('transfer_type', new sfWidgetFormChoice(array('choices' => array_merge(array(''=>'type'),transferPlayerForm::$transferType),'label'=>'转会类型')));
        $this->setValidator('transfer_type', new sfValidatorPass());
  }
  
  public function getFields() {
       $fields = parent::getFields();
       $fields['transfer_type'] = 'Text';
       return $fields;
  }
  
  public function addTransferTypeColumnQuery($query, $field, $value){
        if($value['text'] || (int)$value['text']==0){
            return $query->andWhere('type = ?', $value['text']);         
        }else{
            return $query;
        }
    }
  
}
