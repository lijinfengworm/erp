<?php

/**
 * TrdMessage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdMessage extends BaseTrdMessage
{
    public function getPushStatus() {
        if ($this->getStatus() == 1) return "已推送";
        return "待推送";
    }
}