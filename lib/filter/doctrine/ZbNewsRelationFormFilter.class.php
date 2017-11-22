<?php

/**
 * ZbNewsRelation filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ZbNewsRelationFormFilter extends BaseZbNewsRelationFormFilter
{
  public function configure()
  {
      $this->widgetSchema['news_id']->setOption(
	    'renderer_class', 
	    'sfWidgetFormDoctrineJQueryAutocompleter'
        );
        $this->widgetSchema['news_id']->setOption('renderer_options', array(
        'model' => 'ZbNews',
        'url'   => 'zb_news/searchAjax',
       ));
  }
}
