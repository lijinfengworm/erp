<?php

/**
 * TrdFeedBack filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdFeedBackFormFilter extends BaseTrdFeedBackFormFilter
{
  public function configure()
  {
      unset($this['type']);
  }
}
