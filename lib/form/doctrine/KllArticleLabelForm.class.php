<?php

/**
 * KllArticleLabel form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllArticleLabelForm extends BaseKllArticleLabelForm
{
    public $cate;
    public function __construct($category, $tree){
        $this->cate = $tree;
        parent::__construct($category);
    }
  public function configure()
  {
      $this->setWidget('fa', new sfWidgetFormChoice(['choices' => $this->cate], []));
      $this->setWidget('description', new sfWidgetFormTextarea([], ['placeholder' => '添加描述，可选项']));
  }
}
