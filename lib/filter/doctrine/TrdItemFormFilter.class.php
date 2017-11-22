<?php

/**
 * TrdItem filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdItemFormFilter extends BaseTrdItemFormFilter
{
  public function configure()
  {
    //$this->widgetSchema['user_id'] = new sfWidgetFormFilterInput(array('with_empty' => true));
    //$this->validatorSchema['user_id'] = new hpValidatorDoctrineInput(array('model' => 'TrdUser', 'column' => 'hupu_username'));
  }
}
