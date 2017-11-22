<?php

/**
 * KaluliArticle form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliArticleForm extends BaseKaluliArticleForm
{
    public static $types = array('1'=>'品类描述','2'=>'解决方案');
    public function configure()
    {
        $aid= $this->getObject()->getId();
        $content = '';
        $category = $categoryChild = array();
        if($this->getObject()->getId())
        {
            $attr = KaluliArticleAttrTable::getInstance()->createQuery()->where('article_id = ?',$aid)->fetchOne();
            if($attr) $content = $attr['content'];
        }
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['hits']);

        $tmp = KaluliCategoryTable::getInstance()->createQuery()->where('pid =?',0)->fetchArray();
        if($tmp)foreach($tmp as $v)
        {
            $category[$v['id']] = $v['name'];
        }

        if($this->getObject()->getCategory())
        {
            $tmp = KaluliCategoryTable::getInstance()->createQuery()->where('pid =?',$this->getObject()->getCategory())->fetchArray();
            if($tmp)foreach($tmp as $v)
            {
                $categoryChild[$v['id']] = $v['name'];
            }
        }



        # 标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 18)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 8), array('required' => '标题必填',  'max_length' => '标题不大于18个字', 'min_length' => '标题不少于8个字')));
        # 类型
        $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>self::$types)));
        $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys(self::$types),'required' => true)));//验证
        # 类目

        //  $this->setWidget('category', new sfWidgetFormChoice(array('choices'=>$category)));
        // $this->setValidator('category', new sfValidatorChoice(array('choices'=>array_keys($category))));//验证
        # 子类目
        //     $this->setWidget('category_child', new sfWidgetFormChoice(array('choices'=>$categoryChild)));
        //  $this->setValidator('category_child', new sfValidatorChoice(array()));//验证
        # 简介
        $this->setWidget('intro',new sfWidgetFormTextarea());
        $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '简介必填')));
        # 内容
        $this->setWidget('content',new tradeWidgetFormUeditor(array('button_widget'=>true)));
      //  $this->setDefault('content',$content);
        $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '简介必填')));

        $this->widgetSchema->setHelps(array(
//          'img_path' => '<span style="color: red">图片比例必须1:1</span>',
//          'root_type' => '<span style="color: red">PS：请勿把发给第三方的礼品卡导入到活动当中，以免造成礼品卡重复领取</span>',
        ));
        # 回调
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
    }
    public function myCallback($validator, $values)
    {
//        if(!empty($values['category']) && !empty($values['category_child']) )
//        {
//
//        }

        return $values;
    }
}
