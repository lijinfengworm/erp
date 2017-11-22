<?php

/**
 * TrdShopCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdShopCategory extends BaseTrdShopCategory
{
    function getShops() {
        $info = TrdShopInfoTable::getInstance()
            ->createQuery()
            ->where('shop_category_id = ?', $this->getId())
            ->orderby("id asc")
            ->execute();

        return $info;
    }
}