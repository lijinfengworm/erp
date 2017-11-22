<?php

/**
 * KaluliWarehouses form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliWarehousesForm extends BaseKaluliWarehousesForm
{

    private $_freight_type = array(
        '1'=>'顺风或者圆通',
        '2'=>'包邮',
    );


    public function configure()
  {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['create_date']);


      $this->setWidget('freight_type', new sfWidgetFormChoice(array("choices" => $this->_freight_type)));



  }



    public function processValues($values)
    {
        $values = parent::processValues($values);
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(1);
        $_redis->del("kaluli.ware.list");
        return $values;
    }

}
