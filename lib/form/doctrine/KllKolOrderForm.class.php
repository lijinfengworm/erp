<?php

/**
 * KllKolOrder form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllKolOrderForm extends BaseKllKolOrderForm
{
  public static $status = array(
      0=>'待付款',
      1=>'待发货',
      2=>'已发货',
      3=>'取消',
      4=>'无效',
      5=>'确认收货'
  );

  public function configure()
  {
  }
}
