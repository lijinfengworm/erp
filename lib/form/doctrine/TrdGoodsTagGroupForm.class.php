<?php

/**
 * TrdGoodsTagGroup form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdGoodsTagGroupForm extends BaseTrdGoodsTagGroupForm
{
    public static $clickType = array(
        1=>'单选',
        2=>'多选',
    );

//    public static $isRequire = array(
//        0=>'必填',
//        1=>'非必填',
//    );
  public function configure()
  {
      unset($this['value']);
      unset($this['status']);

      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '名称必填',)));

//      $this->setWidget("type", new sfWidgetFormChoice(array('expanded' => true,"choices" => TrdGoodsForm::$type),array('class'=>'radio')));
//      $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(TrdGoodsForm::$type), 'required' => true), array('invalid' => '请设置类型', 'required'=>'请设置类型')));

      $this->setWidget("click_type", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$clickType),array('class'=>'radio')));
      $this->setValidator('click_type', new sfValidatorChoice(array('choices'=>array_keys(self::$clickType), 'required' => true), array('invalid' => '请设置点击类型', 'required'=>'请设置点击类型')));

//      $this->setWidget("is_require", new sfWidgetFormChoice(array('expanded' => true,"choices" => self::$isRequire),array('class'=>'radio')));
//      $this->setValidator('is_require', new sfValidatorChoice(array('choices'=>array_keys(self::$isRequire), 'required' => true), array('invalid' => '请设置是否必填', 'required'=>'请设置是否必填')));

      # 回调
      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );

  }

    public function myCallback($validator, $values)
    {
        $query = TrdGoodsTagGroupTable::getInstance()->createQuery()->andWhere('name =?',$values['name']);
        if($values['id'])
        {
            $query->andWhere('id != ?',$values['id']);
        }
        $data = $query->fetchArray();
        if(!empty($data))
        {
            throw new sfValidatorError($validator, '分组名称不能重复哦');
        }
    }
}
