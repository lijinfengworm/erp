<?php

/**
 * TrdCommentCluster form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdCommentClusterForm extends BaseTrdCommentClusterForm
{
  public function configure()
  {
      unset($this['to_userid']);
      unset($this['to_username']);
      unset($this['imgs_attr']);
  }
}