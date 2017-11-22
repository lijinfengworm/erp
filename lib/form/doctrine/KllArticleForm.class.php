<?php

/**
 * KllArticle form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllArticleForm extends BaseKllArticleForm
{
    public function configure($article){
        unset($this['created_at']);
        unset($this['updated_at']);
        $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w180')));
        $this->setWidget('abstract', new sfWidgetFormTextarea([], []));
        $this->setWidget('content', new sfWidgetFormTextarea([], ["id" => "trd_product_attr_intro"]));
        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
    }
    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {

        return $values;
    }

}
