<?php

/**
 * TrdFunShop
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdFunShop extends BaseTrdFunShop
{
    function getGoLink() {
        sfProjectConfiguration::getActive()->loadHelpers('TrdLink');

        return shop_go_link($this->getLink());
    }
}