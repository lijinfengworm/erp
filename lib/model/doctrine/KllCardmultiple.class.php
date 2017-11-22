<?php

/**
 * KllCardmultiple
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class KllCardmultiple extends BaseKllCardmultiple
{
        // 0未生成  1生成中  2ok  3生成失败
        public static $_IS_SUCCESS_OK = 2;
        public static $_IS_SUCCESS_CREATEING = 1;
        public static $_IS_SUCCESS_ERROR = 3;

        public static $_STATUS_OK = 1;






    public static function getFormatIsSuccess($flag,$type = 'string'){
        $string = array(0=>'未开始',1=>'生成中',2=>'生成成功',3=>'生成失败');
        $html_one = array(
            0=>'<span class="c-999">未开始</span>',
            1=>'<span class="c-blue">生成中</span>',
            2=>'<span class="c-green">生成成功</span>',
            3=>'<span class="c-red">生成失败</span>');
        $type = $$type;
        if(!empty($type[$flag])) return $type[$flag];
        return false;
    }

    public static function getFormatStatus($flag,$type = 'string'){
        $string = array(1=>'正常',2=>'下线');
        $html_one = array(1=>'<span class="c-green">正常</span>',
            2=>'<span class="c-red">下线</span>');
        $type = $$type;
        if(!empty($type[$flag])) return $type[$flag];
        return false;
    }






}