<?php

/**
 * KllDictionary form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllDictionaryForm extends BaseKllDictionaryForm
{
  public static $itemType = 2; //品牌名

  public static $couponChannelType = 3;//微信活动优惠券渠道

  public static $apiOriginType = 4; //api接口来源参数

  public function configure()
  {
  }
}
