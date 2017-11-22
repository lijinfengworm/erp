<?php

/**
 * comIdentifyTag filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class comIdentifyTagFormFilter extends BasecomIdentifyTagFormFilter
{
  public function configure()
  {
     $sites = array();
     $site  =  SiteTable::getInstance()->findAll();
      foreach($site as $siteinfo)
      {
         $sites[$siteinfo->getId()] =  $siteinfo->getName();
      }
      $this->widgetSchema["user_id"] = new sfWidgetFormSelect(array("choices" => $sites));
  }
}
