<?php

/**
 * ZbSells filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ZbSellsFormFilter extends BaseZbSellsFormFilter
{
  public function configure()
  {
      $this->widgetSchema['product_id']->setOption(
	    'renderer_class', 
	    'sfWidgetFormDoctrineJQueryAutocompleter'
        );
        $this->widgetSchema['product_id']->setOption('renderer_options', array(
        'model' => 'ZbProducts',
        'url'   => 'zb_products/searchAjax',
       ));      
  }
}
