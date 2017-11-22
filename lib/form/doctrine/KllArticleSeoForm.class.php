<?php

/**
 * KllArticleSeo form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllArticleSeoForm extends BaseKllArticleSeoForm
{
	public function __construct($articleFrom){
        parent::__construct($articleFrom);
    }
  public function configure()
  {
      $this->setWidget('description', new sfWidgetFormTextarea([], []));
  }
}
