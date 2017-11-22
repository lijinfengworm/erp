<?php

/**
 * ##MODULE_NAME## actions.
 *
 * @package    HC
 * @subpackage ##MODULE_NAME##
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ##MODULE_NAME##Actions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
}
