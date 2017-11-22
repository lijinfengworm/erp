<?php

/**
 * TrdItemAll filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdItemAllFormFilter extends BaseTrdItemAllFormFilter
{
  public function configure()
  {
  }
  public function setup() {
    parent::setup();
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $this->setWidget('shoe_id', new sfWidgetFormDoctrineJQueryAutocompleter(array(
            'model' => 'trdItem',
            'url' => url_for('trd_item/searchAjax'),
        )));
  }  
}
