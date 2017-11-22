<?php

/**
 * TrdCollectionItems filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdCollectionItemsFormFilter extends BaseTrdCollectionItemsFormFilter
{
  public function configure()
  {
      unset($this["item_all_id"]);
  }
}
