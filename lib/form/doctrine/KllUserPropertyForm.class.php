<?php

/**
 * KllUserProperty form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllUserPropertyForm extends BaseKllUserPropertyForm
{
  public static $_jobs = array(
      1=>"学生",
      2=>"公司白领",
      3=>"国企职员",
      4=>"公务员",
      5=>"私营业主",
      6=>"自由职业",
      7=>"健身教练",
      8=>"专业运动员",
      9=>"军人",
      10=>"艺术家",
      11=>"运动达人",
      99=>"其他"
  );

  public function configure()
  {
  }
}
