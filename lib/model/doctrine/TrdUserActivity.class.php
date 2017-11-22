<?php

/**
 * TrdUserActivity
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdUserActivity extends BaseTrdUserActivity
{
    public static $_lucky_redis_set = 'trademobile2015:daigou:hongbao:share:lucky:set';    //用户中券集合
    public static $type = array(
        1=>'新用户送券',
        2=>'识货七夕女王杯送券',
        3=>'用户下单分享送券',
        4=>'黑5',
        99=>'券库',
    );

    public static $status = array(
        0=>'正常',
        1=>'注销'
    );
    public static $attr = array(
        1=>array(
            'list'=>array(20),
            'lipinka'=>array(
                20=>1,
            )
        ),
        2=>array(
            'list'=>array(10),
            'lipinka'=>array(
                10=>1,
            )
        ),
        3=>array(
            'list'=>array(10, 20, 50),
            'lipinka'=>array()
        ),
        4=>array(
            'list'=>array(20, 30, 50),
            'lipinka'=>array()
        ),
        99=>array(
            'list'=>array(10, 20, 30, 40, 50),
            'lipinka'=>array()
        ),
    );
}
