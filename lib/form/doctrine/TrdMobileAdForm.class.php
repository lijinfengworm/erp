<?php

/**
 * TrdMobileAd form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdMobileAdForm extends BaseTrdMobileAdForm
{
    public function configure()
    {
        unset($this['created_at']);
        unset($this['updated_at']);
        unset($this['grant_uid']);
        unset($this['grant_username']);

        #弹层时间
        $this->setWidget('start_time', new sfWidgetFormInput(array(), array(
            'class' => 'J_date',
            'onclick' => "WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'" . date('Y-m-d', time() + 86400) . "'})",
            'maxlength' => 19,
            'size' => 20
        )));

        $this->setValidator('start_time', new sfValidatorString(array('required' => true)));
        $this->setDefault('start_time', date('Y-m-d', strtotime("+1 day")));

        $this->setWidget('end_time', new sfWidgetFormInput(array(), array(
            'class' => 'J_date',
            'onclick' => "WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'" . date('Y-m-d', time() + 86400 * 2) . "'})",
            'maxlength' => 19,
            'size' => 20
        )));

        $this->setValidator('end_time', new sfValidatorString(array('required' => true)));
        $this->setDefault('end_time', date('Y-m-d', strtotime("+2 day")));

        #任务说明
        $this->setWidget('description', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('description',
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '请填写任务说明')));

        #上传弹层图片
        $rule = array(
            'required' => true,
            'max_size' => '51200',
            'path' => 'uploads/trade/mobile_ad',
            'height' => 300,
            'width'=> 500
        );

        $this->setWidget('upload_banner_path',
            new tradeWidgetFormKupload(array("callback" => "displayBannerImage(data.url);", "rule" => $rule)));
        $this->setWidget('banner_img_path', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 300)));
        $this->setValidator('banner_img_path', new sfValidatorUrl(array('required' => true, 'trim' => true),
            array('required' => '弹层图片必填', 'invalid' => '弹层图片必填')));

        #跳转按钮设置
        $this->setWidget('r_content', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('r_content',
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '请填写跳转按钮文稿')));
        $this->setWidget('r_content_color', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('r_content_color', new sfValidatorString(array('trim' => true), array('required' => '跳转文字色号必填')));
        $this->setWidget('r_url', new sfWidgetFormInput(array(), array('size' => 100, 'maxlength' => 100)));
        $this->setValidator('r_url',
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '跳转链接必填')));
        $this->setWidget('r_color', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('r_color', new sfValidatorString(array('trim' => true), array('required' => '跳转按钮色号必填')));


        #退出按钮设置
        $this->setWidget('c_content', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('c_content',
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '请填写关闭按钮文稿')));
        $this->setWidget('c_content_color', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('c_content_color', new sfValidatorString(array('trim' => true), array('required' => '跳转文字色号必填')));
        $this->setWidget('c_color', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 50)));
        $this->setValidator('c_color', new sfValidatorString(array('trim' => true), array('required' => '关闭按钮色号必填')));

    }

    public function processValues($values)
    {
        $values = parent::processValues($values);
        $uid = sfContext::getInstance()->getUser()->getTrdUserHuPuId();
        $username = sfContext::getInstance()->getUser()->getTrdUsername();
        if ($this->isNew()) {
            $values['grant_uid'] = $uid;
            $values['grant_username'] = $username;
        }

        return $values;
    }
}
