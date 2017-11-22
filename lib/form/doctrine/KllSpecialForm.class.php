<?php

/**
 * KllSpecial form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllSpecialForm extends BaseKllSpecialForm
{
    public $cate;
    public function __construct($specialFrom, $tree){
        $this->cate = $tree;
        parent::__construct($specialFrom);
    }
  public function configure()
  {
      $this->setWidget('cid', new sfWidgetFormChoice(['choices' => $this->cate], []));
      $this->setWidget('is_use', new sfWidgetFormSelectRadio(["choices" => ['1' => '启用','0' => '备用']]));
      $this->setWidget('description', new sfWidgetFormTextarea([], ['placeholder' => '添加描述，可选项']));
  }
}
