<?php

/**
 * TrdSearchHistory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdSearchHistory extends BaseTrdSearchHistory
{
    public function getChannel(){
        if ($this->type == 0) return '全部优惠';
        if ($this->type == 1) return '国内';
        if ($this->type == 2) return '海淘';
        if ($this->type == 3) return '发现';
    }
    
    public function getTimeformat(){
        return date('Y-m-d',$this->time);
    }
}