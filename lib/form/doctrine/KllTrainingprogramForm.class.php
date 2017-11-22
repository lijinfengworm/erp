<?php

/**
 * KllTrainingprogram form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllTrainingprogramForm extends BaseKllTrainingprogramForm
{
  public function configure()
  {
      $rule = array(
          'required'=>true,
          'max_size'=>'500000',
          'path'=>'uploads/kaluli/attachment',
      );
      $this->setWidget('cover',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
      $this->setWidget('content',new tradeWidgetFormUeditor(array(),["id" => "trd_product_attr_intro","row" => "3", 'water' => 0]));
  }
}
