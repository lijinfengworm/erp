<?php

/**
 * KaluliTags form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliTagsForm extends BaseKaluliTagsForm
{
    public static $types = array(
        1=>'解决方案',
        2=>'品类名称',
        3=>'适用人群'
    );
  public function configure()
  {
      # 标题
      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40), array('required' => '名称必填',  'max_length' => '名称不大于50个字')));
      # 父标签

      $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>self::$types)));
      $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(self::$types),'required' => true)));//验证

      ## 权重
      $this->setWidget("weight",new sfWidgetFormInput(array(),array("size" =>10,"maxlength" =>50)));
      $this->setValidator("weight",new sfValidatorString(array("trim"=>true,"max_length" => 40),array()));
  }

    /**
     * @param $info
     * @param $type  1.解决方案 2.标签,3.适用人群
     */
  public static function sortTages($info,$type) {
      if(empty($info)) {
          return array();
      }
      $tags = KaluliTagsTable::getInstance()->createQuery()->where("type = ?",$type)->fetchArray();
      $tags = KaluliFun::my_sort($tags,"weight",SORT_DESC);
      $sortInfo = array();
      foreach($tags as $k=>$v) {
          foreach($info as $ik => $iv) {
              if($v['name'] == $iv) {
                  $sortInfo[] = $iv;
              }
          }
      }

      return $sortInfo;

  }
}
