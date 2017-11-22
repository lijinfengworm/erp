<?php

/**
 * wpTransferHistory filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wpTransferHistoryFormFilter extends BasewpTransferHistoryFormFilter
{
  public function configure()
  {
    $this->widgetSchema['wpserver_id'] = new sfWidgetFormChoice(array(
                                              'choices' =>  wpServerTable::getServersWithGameName()));
                
    $this->widgetSchema['wporder_id'] = new sfWidgetFormInput();   
  }
}
