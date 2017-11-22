<?php

/**
 * TrdFeedBack
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdFeedBack extends BaseTrdFeedBack
{
    public function getCreated(){
        return date('y/m/d H:i', strtotime($this->getCreatedAt()));
    }
    
    public function getPartialContent(){
        return common::utf_substr($this->getContent(),30);
    }
}
