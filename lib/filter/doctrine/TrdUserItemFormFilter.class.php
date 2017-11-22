<?php

/**
 * TrdUserItem filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdUserItemFormFilter extends BaseTrdUserItemFormFilter
{
  public function configure()
  {
    unset($this["created_at"]);
    unset($this["updated_at"]);   
  }
  public function setup() {
    
    parent::setup();
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    
    $this->setWidget('user_id', new sfWidgetFormDoctrineJQueryAutocompleter(array(
            'model' => 'trdUser',
            'url' => url_for('trd_user/searchAjax'),
        )));
    $this->setWidget('item_id', new sfWidgetFormDoctrineJQueryAutocompleter(array(
            'model' => 'trdItem',
            'url' => url_for('trd_item/searchAjax'),
        )));
    $this->setWidget('item_all_id', new sfWidgetFormDoctrineJQueryAutocompleter(array(
            'model' => 'trdItemAll',
            'url' => url_for('trd_item_all/searchAjax'))));     
  }
}