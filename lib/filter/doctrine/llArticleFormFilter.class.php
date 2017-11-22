<?php

/**
 * llArticle filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class llArticleFormFilter extends BasellArticleFormFilter {

    public function configure() {
        $this->widgetSchema["type"] = new sfWidgetFormSelect(array("choices" => array(1 => "文字", 2 => "图片集", 3 => "视频", 4 => "视频集",)));
        $this->widgetSchema["zone_type"] = new sfWidgetFormSelect(array("choices" => array(2 => "常规", 1 => "置顶", 3 => "右侧图片",)));
        $this->widgetSchema["zone_index"] = new sfWidgetFormSelect(array("choices" => array(0 => "-------", 1 => "左上", 2 => "右上", 3 => "左下", 4 => "右下")));
        $this->widgetSchema["video_type"] = new sfWidgetFormSelect(array("choices" => array(0 => "请选择", 1 => "篮球", 2 => "足球", 3 => "F1")));
    }

}
