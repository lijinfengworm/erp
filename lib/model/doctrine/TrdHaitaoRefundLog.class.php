<?php

/**
 * TrdHaitaoRefundLog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdHaitaoRefundLog extends BaseTrdHaitaoRefundLog
{
    public function getHandleStatus(){
        if($this->getStatus() == 0) return '退款中';
        return '退款完成';
    }
}