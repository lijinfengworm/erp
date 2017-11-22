<?php

/**
 * touchScreenTextChain filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class touchScreenTextChainFormFilter extends BasetouchScreenTextChainFormFilter
{
  public function configure()
  {
      unset ($this['created_at'], $this['updated_at']);
  }
}
