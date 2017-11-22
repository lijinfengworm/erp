<?php

/**
 * KllArticles form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllArticlesForm extends BaseKllArticlesForm
{
    public $isWater;
    public function __construct($articleFrom, $isWater){
        $this->isWater = $isWater;
        parent::__construct($articleFrom);
    }
    public function configure(){
        unset($this['created_at']);
        unset($this['updated_at']);
        //$this->setWidget('label', new sfWidgetFormSelectCheckbox(["choices" => $this->labels]));
        $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w180')));
        $this->setWidget('abstract', new sfWidgetFormTextarea([], []));
        $this->setWidget('content',new tradeWidgetFormUeditor(array(),["id" => "trd_product_attr_intro","row" => "3", 'water' => $this->isWater]));
        /*$this->setWidget('is_video', new sfWidgetFormSelectRadio(["choices" => ['0' => '否', '1' => '是'] ]));*/
        $this->setWidget('order', new sfWidgetFormInput([], ['class'=>'w180']));

        # 上传图片
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            //    'height'=>400,
            //    'width'=>400,
            'path'=>'uploads/kaluli/train',
            //   'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        for($i=1; $i<1000; $i++){
            $this->setWidget('upload_path'.$i,new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url, $i);","rule"=>$rule)));
        }


        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );

        $this->widgetSchema->setHelps(array(
            'upload_path' => '<span class="c-999">请上传尺寸为800*450px的图片，72dpi分辨率，

图片大小不超过100KB</span>',

        ));
    }
    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {

        return $values;
    }
}
